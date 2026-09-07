<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class EspayService
{
    /**
     * Generate QRIS menggunakan Espay SNAP API.
     */
    public function generateQris(Payment $payment): array
    {
        $payment->loadMissing('order');

        $order = $payment->order;

        if (! $order) {
            throw new RuntimeException(
                'Order untuk payment tidak ditemukan.'
            );
        }

        $this->validateQrisConfiguration();

        if (! $payment->expired_at) {
            throw new RuntimeException(
                'Payment belum memiliki waktu expired.'
            );
        }

        /*
         * partnerReferenceNo:
         * - maksimal 32 karakter
         * - alphanumeric
         * - harus unik
         *
         * Contoh:
         * DS15P12
         */
        $partnerReferenceNo = $this->generatePartnerReference(
            $order->id,
            $payment->id
        );

        /*
         * Timestamp menggunakan timezone Jakarta.
         */
        $timestamp = now('Asia/Jakarta')
            ->format('Y-m-d\TH:i:sP');

        /*
         * X-EXTERNAL-ID:
         * numeric dan unik pada hari yang sama.
         */
        $externalId = $this->generateExternalId(
            $payment->id
        );

        /*
         * Request body.
         *
         * JSON ini juga yang digunakan untuk
         * membuat signature.
         */
        $body = [
            'partnerReferenceNo' => $partnerReferenceNo,

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

            // 'validityPeriod' => $payment->expired_at
            //     ->copy()
            //     ->timezone('Asia/Jakarta')
            //     ->format('Y-m-d\TH:i:sP'),

        ];

        /*
         * JSON harus sama persis dengan JSON
         * yang dikirim ke Espay.
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
            'espay.qris_endpoint',
            '/api/v1.0/qr/qr-mpm-generate'
        );

        /*
         * Generate asymmetric RSA SHA-256 signature.
         */
        $signature = $this->generateSignature(
            httpMethod: 'POST',
            relativeUrl: $relativeUrl,
            body: $jsonBody,
            timestamp: $timestamp
        );

        /*
         * Header SNAP Espay.
         */
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

        /*
         * URL endpoint.
         */
        $url = $this->buildUrl($relativeUrl);

        /*
         * DEBUG LOG
         *
         * Jangan pernah log:
         * - API Key
         * - Password
         * - Private Key
         * - X-SIGNATURE
         */
        Log::info('ESPay QRIS Payload', [
            'body' => $body,

            'headers' => [
                'Content-Type' =>
                $headers['Content-Type'] ?? null,

                'X-TIMESTAMP' =>
                $headers['X-TIMESTAMP'] ?? null,

                'X-EXTERNAL-ID' =>
                $headers['X-EXTERNAL-ID'] ?? null,

                'X-PARTNER-ID' =>
                $headers['X-PARTNER-ID'] ?? null,

                'CHANNEL-ID' =>
                $headers['CHANNEL-ID'] ?? null,
            ],

            'json_body' => $jsonBody,

            'relative_url' => $relativeUrl,

            'url' => $url,
        ]);

        /*
         * Log request ringkas.
         */
        Log::info('ESPay QRIS Request', [
            'order_number' => $order->order_number,

            'payment_number' =>
            $payment->payment_number,

            'partner_reference_no' =>
            $partnerReferenceNo,

            'external_id' =>
            $externalId,

            'merchant_code' =>
            config('espay.merchant_code'),

            'product_code' =>
            config(
                'espay.product_code',
                'QRIS'
            ),

            'url' => $url,

            'timestamp' => $timestamp,
        ]);

        /*
         * Kirim request ke Espay.
         */
        $response = Http::timeout(
            (int) config(
                'espay.timeout',
                30
            )
        )
            ->withHeaders($headers)
            ->withBody(
                $jsonBody,
                'application/json'
            )
            ->post($url);

        /*
         * Handle response Espay.
         */
        return $this->handleQrisResponse(
            $response,
            $payment,
            $externalId
        );
    }

    /**
     * Inquiry Merchant Info Espay.
     *
     * Digunakan untuk memastikan API Key valid
     * dan melihat produk yang tersedia.
     */
    public function merchantInfo(): array
    {
        $apiKey = config('espay.api_key');

        if (! $apiKey) {
            throw new RuntimeException(
                'ESPAY_API_KEY belum dikonfigurasi.'
            );
        }

        $url = rtrim(
            config('espay.base_url'),
            '/'
        ) . '/rest/merchant/merchantinfo';

        $response = Http::asForm()
            ->timeout(
                (int) config(
                    'espay.timeout',
                    30
                )
            )
            ->post($url, [
                'key' => $apiKey,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(
                'Espay Merchant Info HTTP ' .
                    $response->status() .
                    ': ' .
                    $response->body()
            );
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException(
                'Response Merchant Info Espay tidak valid.'
            );
        }

        if (
            ($data['error_code'] ?? null) !==
            '0000'
        ) {
            throw new RuntimeException(
                'Espay Merchant Info error [' .
                    ($data['error_code'] ?? 'UNKNOWN') .
                    ']: ' .
                    ($data['error_message'] ??
                        'Unknown error')
            );
        }

        return $data;
    }

    /**
     * Generate partner reference number.
     *
     * Format:
     * DS{order_id}P{payment_id}
     *
     * Contoh:
     * DS15P12
     */
    private function generatePartnerReference(
        int $orderId,
        int $paymentId
    ): string {
        return 'DS' .
            $orderId .
            'P' .
            $paymentId;
    }

    /**
     * Generate X-EXTERNAL-ID.
     *
     * Numeric dan unique pada hari yang sama.
     */
    private function generateExternalId(
        int $paymentId
    ): string {
        return now('Asia/Jakarta')
            ->format('YmdHisv') .
            str_pad(
                (string) $paymentId,
                8,
                '0',
                STR_PAD_LEFT
            );
    }

    /**
     * Generate RSA SHA-256 asymmetric signature.
     *
     * StringToSign:
     *
     * HTTPMethod:
     * RelativeUrl:
     * SHA256(minified JSON):
     * Timestamp
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

        if (! $privateKeyPath) {
            throw new RuntimeException(
                'ESPAY_PRIVATE_KEY_PATH belum dikonfigurasi.'
            );
        }

        /*
         * Jika path relatif, ubah menjadi absolute path
         * berdasarkan root Laravel.
         */
        if (
            ! str_starts_with(
                $privateKeyPath,
                DIRECTORY_SEPARATOR
            ) &&
            ! preg_match(
                '/^[A-Za-z]:[\\\\\/]/',
                $privateKeyPath
            )
        ) {
            $privateKeyPath = base_path(
                $privateKeyPath
            );
        }

        if (! is_file($privateKeyPath)) {
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
         * SHA-256 body.
         */
        $bodyHash = strtolower(
            hash('sha256', $body)
        );

        /*
         * StringToSign SNAP.
         */
        $stringToSign =
            strtoupper($httpMethod) .
            ':' .
            $relativeUrl .
            ':' .
            $bodyHash .
            ':' .
            $timestamp;

        /*
         * Load RSA private key.
         */
        $privateKeyResource =
            openssl_pkey_get_private(
                $privateKey
            );

        if ($privateKeyResource === false) {
            throw new RuntimeException(
                'Private key Espay tidak valid.'
            );
        }

        /*
         * Sign menggunakan SHA-256.
         */
        $signature = '';

        $success = openssl_sign(
            $stringToSign,
            $signature,
            $privateKeyResource,
            OPENSSL_ALGO_SHA256
        );

        if (! $success) {
            throw new RuntimeException(
                'Gagal membuat RSA SHA-256 signature Espay.'
            );
        }

        return base64_encode(
            $signature
        );
    }

    /**
     * Build full Espay URL.
     */
    private function buildUrl(
        string $relativeUrl
    ): string {
        return rtrim(
            config('espay.base_url'),
            '/'
        ) .
            '/' .
            ltrim(
                $relativeUrl,
                '/'
            );
    }

    /**
     * Validate konfigurasi QRIS.
     */
    private function validateQrisConfiguration(): void
    {
        $required = [
            'espay.base_url' =>
            config('espay.base_url'),

            'espay.merchant_code' =>
            config('espay.merchant_code'),

            'espay.product_code' =>
            config('espay.product_code'),

            'espay.channel_id' =>
            config('espay.channel_id'),

            'espay.private_key_path' =>
            config('espay.private_key_path'),

            'espay.qris_endpoint' =>
            config('espay.qris_endpoint'),
        ];

        foreach ($required as $key => $value) {
            if (
                $value === null ||
                $value === ''
            ) {
                throw new RuntimeException(
                    "Konfigurasi Espay '{$key}' belum tersedia."
                );
            }
        }
    }

    /**
     * Handle response Generate QRIS.
     */
    private function handleQrisResponse(
        Response $response,
        Payment $payment,
        string $externalId
    ): array {
        $data = $response->json();

        Log::info('ESPay QRIS Response', [
            'payment_number' =>
            $payment->payment_number,

            'http_status' =>
            $response->status(),

            'response_code' =>
            $data['responseCode'] ?? null,

            'response_message' =>
            $data['responseMessage'] ?? null,

            'has_qr_url' =>
            ! empty($data['qrUrl']),

            'has_qr_content' =>
            ! empty($data['qrContent']),
        ]);

        /*
         * HTTP error.
         */
        if (! $response->successful()) {
            throw new RuntimeException(
                'ESPay HTTP Error: ' .
                    $response->status() .
                    ' - ' .
                    $response->body()
            );
        }

        /*
         * Response harus berupa JSON object.
         */
        if (! is_array($data)) {
            throw new RuntimeException(
                'Response QRIS Espay tidak valid.'
            );
        }

        /*
         * Success:
         * 2004700
         */
        if (
            ($data['responseCode'] ?? null) !==
            '2004700'
        ) {
            throw new RuntimeException(
                'ESPay menolak request QRIS: ' .
                    ($data['responseCode'] ??
                        'NO_RESPONSE_CODE') .
                    ' - ' .
                    ($data['responseMessage'] ??
                        'Unknown error') .
                    ' | RAW: ' .
                    json_encode(
                        $data,
                        JSON_UNESCAPED_SLASHES |
                            JSON_UNESCAPED_UNICODE
                    )
            );
        }

        return [
            'response_code' =>
            $data['responseCode'] ?? null,

            'response_message' =>
            $data['responseMessage'] ?? null,

            'qr_url' =>
            $data['qrUrl'] ?? null,

            'qr_content' =>
            $data['qrContent'] ?? null,

            'qr_image' =>
            $data['qrImage'] ?? null,

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

            'external_id' =>
            $externalId,

            'raw' =>
            $data,
        ];
    }
}
