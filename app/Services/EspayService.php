<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class EspayService
{
    /**
     * Generate QRIS melalui Espay SNAP API.
     */
    public function generateQris(Payment $payment): array
    {
        $order = $payment->order;

        if (!$order) {
            throw new RuntimeException(
                'Order untuk payment tidak ditemukan.'
            );
        }

        $timestamp = now('Asia/Jakarta')
            ->format('Y-m-d\TH:i:sP');

        /*
         * X-EXTERNAL-ID harus numeric dan unique.
         *
         * Timestamp millisecond + payment ID.
         */
        $externalId =
            now('Asia/Jakarta')->format('YmdHisv') .
            str_pad(
                (string) $payment->id,
                8,
                '0',
                STR_PAD_LEFT
            );

        $body = [
            'partnerReferenceNo' => $order->order_number,

            'merchantId' => config(
                'espay.merchant_code'
            ),

            'amount' => [
                'value' => number_format(
                    (float) $payment->amount,
                    2,
                    '.',
                    ''
                ),
                'currency' => 'IDR',
            ],

            'additionalInfo' => [
                'productCode' => config(
                    'espay.product_code',
                    'QRIS'
                ),
            ],

            'validityPeriod' => $payment->expired_at
                ->copy()
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d\TH:i:sP'),
        ];

        /*
         * JSON yang digunakan untuk signature HARUS sama
         * dengan JSON yang dikirim ke Espay.
         */
        $jsonBody = json_encode(
            $body,
            JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE
        );

        if ($jsonBody === false) {
            throw new RuntimeException(
                'Gagal membuat JSON request Espay.'
            );
        }

        $relativeUrl = config(
            'espay.qris_endpoint'
        );

        $signature = $this->generateSignature(
            httpMethod: 'POST',
            relativeUrl: $relativeUrl,
            body: $jsonBody,
            timestamp: $timestamp
        );

        $headers = [
            'Content-Type' => 'application/json',
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'X-EXTERNAL-ID' => $externalId,
            'X-PARTNER-ID' => config(
                'espay.merchant_code'
            ),
            'CHANNEL-ID' => config(
                'espay.channel_id',
                'ESPAY'
            ),
        ];

        $url = rtrim(
            config('espay.base_url'),
            '/'
        ) . '/' . ltrim($relativeUrl, '/');

        Log::info('ESPay QRIS Request', [
            'order_number' => $order->order_number,
            'payment_number' => $payment->payment_number,
            'url' => $url,
            'external_id' => $externalId,
            'body' => $body,

            // Jangan log private key.
            'timestamp' => $timestamp,
        ]);

        $response = Http::timeout(
            config('espay.timeout', 30)
        )
            ->withHeaders($headers)
            ->withBody(
                $jsonBody,
                'application/json'
            )
            ->post($url);

        $data = $response->json();

        Log::info('ESPay QRIS Response', [
            'order_number' => $order->order_number,
            'payment_number' => $payment->payment_number,
            'http_status' => $response->status(),
            'response' => $data,
        ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'ESPay HTTP Error: ' .
                    $response->status() .
                    ' - ' .
                    $response->body()
            );
        }

        /*
         * Success response QRIS SNAP:
         * responseCode = 2004700
         */
        if (!isset($data['responseCode']) || $data['responseCode'] !== '2004700') {
            throw new RuntimeException(
                'ESPay menolak request QRIS: ' .
                    ($data['responseCode'] ?? 'NO_RESPONSE_CODE') .
                    ' - ' .
                    ($data['responseMessage'] ?? 'Unknown error') .
                    ' | RAW: ' .
                    json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }

        return [
            'response_code' => $data['responseCode'] ?? null,
            'response_message' => $data['responseMessage'] ?? null,

            'qr_url' => $data['qrUrl'] ?? null,
            'qr_content' => $data['qrContent'] ?? null,
            'qr_image' => $data['qrImage'] ?? null,

            'reference_no' =>
            data_get(
                $data,
                'additionalInfo.referenceNo'
            ),

            'partner_reference_no' =>
            data_get(
                $data,
                'additionalInfo.partnerReferenceNo'
            ),

            'amount' =>
            data_get(
                $data,
                'additionalInfo.amount'
            ),

            'external_id' => $externalId,

            'raw' => $data,
        ];
    }

    /**
     * Generate RSA SHA-256 asymmetric signature.
     */
    private function generateSignature(
        string $httpMethod,
        string $relativeUrl,
        string $body,
        string $timestamp
    ): string {
        $privateKeyPath = config(
            'espay.private_key_path'
        );

        /*
         * Kalau config menggunakan:
         * storage/app/private/espay/private.key
         */
        if (
            !str_starts_with($privateKeyPath, DIRECTORY_SEPARATOR) &&
            !preg_match('/^[A-Za-z]:[\\\\\/]/', $privateKeyPath)
        ) {
            $privateKeyPath = base_path(
                $privateKeyPath
            );
        }
        if (!file_exists($privateKeyPath)) {
            throw new RuntimeException(
                "Private key Espay tidak ditemukan: {$privateKeyPath}"
            );
        }

        $privateKey = file_get_contents(
            $privateKeyPath
        );

        if ($privateKey === false) {
            throw new RuntimeException(
                'Gagal membaca private key Espay.'
            );
        }

        /*
         * SHA256 body.
         */
        $bodyHash = strtolower(
            hash('sha256', $body)
        );

        /*
         * SNAP StringToSign:
         *
         * HTTPMethod:RelativeUrl:SHA256(body):Timestamp
         */
        $stringToSign =
            strtoupper($httpMethod) .
            ':' .
            $relativeUrl .
            ':' .
            $bodyHash .
            ':' .
            $timestamp;

        $privateKeyResource = openssl_pkey_get_private(
            $privateKey
        );

        if ($privateKeyResource === false) {
            throw new RuntimeException(
                'Private key Espay tidak valid.'
            );
        }

        $signature = '';

        $success = openssl_sign(
            $stringToSign,
            $signature,
            $privateKeyResource,
            OPENSSL_ALGO_SHA256
        );

        if (!$success) {
            throw new RuntimeException(
                'Gagal membuat RSA SHA-256 signature Espay.'
            );
        }

        return base64_encode($signature);
    }
}
