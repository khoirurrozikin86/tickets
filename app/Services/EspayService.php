<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class EspayService
{
    private const DEFAULT_QRIS_ENDPOINT = '/api/v1.0/qr/qr-mpm-generate';

    private const DEFAULT_PRODUCT_CODE = 'QRIS';

    private const DEFAULT_CHANNEL_ID = 'ESPAY';

    private const QRIS_VALIDITY_MINUTES = 10;

    private const QRIS_SUCCESS_CODE = '2004700';

    /**
     * Generate QRIS payment melalui Espay SNAP API.
     */
    public function generateQris(Payment $payment): array
    {
        $payment->loadMissing('order');

        $this->validatePayment($payment);

        $order = $payment->order;

        $qrExpiredAt = now('Asia/Jakarta')
            ->addMinutes(self::QRIS_VALIDITY_MINUTES);

        $partnerReferenceNo = $this->generatePartnerReference(
            $order->id,
            $payment->id
        );

        $timestamp = now('Asia/Jakarta')
            ->format('Y-m-d\TH:i:sP');

        $externalId = $this->generateExternalId(
            $payment->id
        );

        $body = $this->buildQrisPayload(
            payment: $payment,
            partnerReferenceNo: $partnerReferenceNo,
            qrExpiredAt: $qrExpiredAt,
        );

        $jsonBody = $this->encodeJson($body);

        $relativeUrl = config(
            'espay.qris_endpoint',
            self::DEFAULT_QRIS_ENDPOINT
        );

        $signature = $this->generateSignature(
            httpMethod: 'POST',
            relativeUrl: $relativeUrl,
            body: $jsonBody,
            timestamp: $timestamp,
        );

        $headers = $this->buildHeaders(
            timestamp: $timestamp,
            signature: $signature,
            externalId: $externalId,
        );

        $url = $this->buildUrl($relativeUrl);

        $this->logQrisRequest(
            payment: $payment,
            order: $order,
            body: $body,
            headers: $headers,
            jsonBody: $jsonBody,
            relativeUrl: $relativeUrl,
            url: $url,
        );

        $response = $this->sendQrisRequest(
            url: $url,
            headers: $headers,
            jsonBody: $jsonBody,
        );

        $this->logQrisFullResponse(
            $payment,
            $response,
        );

        $qris = $this->handleQrisResponse(
            response: $response,
            payment: $payment,
            externalId: $externalId,
        );

        /*
         * Hanya update expiry jika QRIS berhasil dibuat.
         */
        $payment->update([
            'expired_at' => $qrExpiredAt,
        ]);

        return $qris;
    }

    /**
     * Inquiry Merchant Info Espay.
     */
    public function merchantInfo(): array
    {
        $apiKey = config('espay.api_key');

        if (! $apiKey) {
            throw new RuntimeException(
                'ESPAY_API_KEY belum dikonfigurasi.'
            );
        }

        $url = $this->buildUrl('/rest/merchant/merchantinfo');

        $response = Http::asForm()
            ->timeout($this->getTimeout())
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

        if (($data['error_code'] ?? null) !== '0000') {
            throw new RuntimeException(
                'Espay Merchant Info error [' .
                    ($data['error_code'] ?? 'UNKNOWN') .
                    ']: ' .
                    ($data['error_message'] ?? 'Unknown error')
            );
        }

        return $data;
    }

    /**
     * Build QRIS request payload.
     */
    private function buildQrisPayload(
        Payment $payment,
        string $partnerReferenceNo,
        \Carbon\CarbonInterface $qrExpiredAt,
    ): array {
        return [
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
                    self::DEFAULT_PRODUCT_CODE
                ),
            ],

            'validityPeriod' => $qrExpiredAt
                ->copy()
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d\TH:i:sP'),
        ];
    }

    /**
     * Build SNAP headers.
     */
    private function buildHeaders(
        string $timestamp,
        string $signature,
        string $externalId,
    ): array {
        return [
            'Content-Type' => 'application/json',
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'X-EXTERNAL-ID' => $externalId,
            'X-PARTNER-ID' => config(
                'espay.merchant_code'
            ),
            'CHANNEL-ID' => config(
                'espay.channel_id',
                self::DEFAULT_CHANNEL_ID
            ),
        ];
    }

    /**
     * Send QRIS request ke Espay.
     */
    private function sendQrisRequest(
        string $url,
        array $headers,
        string $jsonBody,
    ): Response {
        try {
            return Http::timeout($this->getTimeout())
                ->withHeaders($headers)
                ->withBody(
                    $jsonBody,
                    'application/json'
                )
                ->post($url);
        } catch (\Throwable $e) {
            Log::error('ESPay QRIS HTTP Exception', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException(
                'Gagal menghubungi Espay QRIS: ' .
                    $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * Handle response Generate QRIS.
     */
    private function handleQrisResponse(
        Response $response,
        Payment $payment,
        string $externalId,
    ): array {
        $data = $response->json();

        Log::info('ESPay QRIS Response', [
            'payment_number' => $payment->payment_number,
            'http_status' => $response->status(),
            'response_code' => is_array($data)
                ? ($data['responseCode'] ?? null)
                : null,
            'response_message' => is_array($data)
                ? ($data['responseMessage'] ?? null)
                : null,
            'has_qr_url' => is_array($data)
                && ! empty($data['qrUrl']),
            'has_qr_content' => is_array($data)
                && ! empty($data['qrContent']),
            'has_qr_image' => is_array($data)
                && ! empty($data['qrImage']),
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

        $responseCode = $data['responseCode'] ?? null;

        /*
         * Espay success.
         */
        if ($responseCode !== self::QRIS_SUCCESS_CODE) {
            throw new RuntimeException(
                'ESPay menolak request QRIS: ' .
                    ($responseCode ?? 'NO_RESPONSE_CODE') .
                    ' - ' .
                    ($data['responseMessage'] ?? 'Unknown error') .
                    ' | RAW: ' .
                    $this->safeJsonEncode($data)
            );
        }

        return [
            'response_code' => $responseCode,

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

            'external_id' => $externalId,

            'raw' => $data,
        ];
    }

    /**
     * Generate partner reference number.
     *
     * Format:
     * DS{orderId}P{paymentId}
     *
     * Contoh:
     * DS18P15
     */
    private function generatePartnerReference(
        int $orderId,
        int $paymentId,
    ): string {
        $reference = 'DS' .
            $orderId .
            'P' .
            $paymentId;

        if (
            strlen($reference) > 32 ||
            ! preg_match('/^[A-Za-z0-9]+$/', $reference)
        ) {
            throw new RuntimeException(
                'Partner reference number Espay tidak valid: ' .
                    $reference
            );
        }

        return $reference;
    }

    /**
     * Generate X-EXTERNAL-ID.
     *
     * Numeric dan unique pada hari yang sama.
     */
    private function generateExternalId(
        int $paymentId,
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
     * Generate asymmetric RSA SHA-256 signature.
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
        string $timestamp,
    ): string {
        $privateKeyPath = config(
            'espay.private_key_path'
        );

        if (! $privateKeyPath) {
            throw new RuntimeException(
                'ESPAY_PRIVATE_KEY_PATH belum dikonfigurasi.'
            );
        }

        $privateKeyPath = $this->resolvePrivateKeyPath(
            $privateKeyPath
        );

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

        $bodyHash = strtolower(
            hash('sha256', $body)
        );

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
    
    
Log::info('ESPay QRIS SIGNATURE DEBUG', [
    'method' => 'POST',
    'relative_url' => $relativeUrl,
    'body_hash' => $bodyHash,
    'timestamp' => $timestamp,
    'string_to_sign' => $stringToSign,
    'signature_length' => strlen($signature),
]);
    
    

        if (! $success) {
            throw new RuntimeException(
                'Gagal membuat RSA SHA-256 signature Espay.'
            );
        }

        return base64_encode($signature);
    }

    /**
     * Resolve private key path.
     */
    private function resolvePrivateKeyPath(
        string $path
    ): string {
        if (
            str_starts_with(
                $path,
                DIRECTORY_SEPARATOR
            ) ||
            preg_match(
                '/^[A-Za-z]:[\\\\\/]/',
                $path
            )
        ) {
            return $path;
        }

        return base_path($path);
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
     * Encode JSON dengan format yang konsisten.
     */
    private function encodeJson(
        array $data
    ): string {
        $json = json_encode(
            $data,
            JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE
        );

        if ($json === false) {
            throw new RuntimeException(
                'Gagal membuat JSON request Espay: ' .
                    json_last_error_msg()
            );
        }

        return $json;
    }

    /**
     * Safe JSON untuk logging/error.
     */
    private function safeJsonEncode(
        mixed $data
    ): string {
        $json = json_encode(
            $data,
            JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE
        );

        return $json !== false
            ? $json
            : 'JSON_ENCODE_ERROR';
    }

    /**
     * Validate payment sebelum Generate QRIS.
     */
    private function validatePayment(
        Payment $payment
    ): void {
        if (! $payment->order) {
            throw new RuntimeException(
                'Order untuk payment tidak ditemukan.'
            );
        }

        $this->validateQrisConfiguration();

        if (
            ! $payment->amount ||
            (float) $payment->amount <= 0
        ) {
            throw new RuntimeException(
                'Nominal payment QRIS tidak valid.'
            );
        }

        if (
            $payment->status === 'PAID'
        ) {
            throw new RuntimeException(
                'Payment sudah dibayar.'
            );
        }

        if (
            $payment->status === 'CANCELLED'
        ) {
            throw new RuntimeException(
                'Payment sudah dibatalkan.'
            );
        }
    }

    /**
     * Validate konfigurasi Espay QRIS.
     */
    private function validateQrisConfiguration(): void
    {
        $required = [
            'espay.base_url' =>
            config('espay.base_url'),

            'espay.merchant_code' =>
            config('espay.merchant_code'),

            'espay.product_code' =>
            config(
                'espay.product_code',
                self::DEFAULT_PRODUCT_CODE
            ),

            'espay.channel_id' =>
            config(
                'espay.channel_id',
                self::DEFAULT_CHANNEL_ID
            ),

            'espay.private_key_path' =>
            config('espay.private_key_path'),

            'espay.qris_endpoint' =>
            config(
                'espay.qris_endpoint',
                self::DEFAULT_QRIS_ENDPOINT
            ),
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
     * Get HTTP timeout.
     */
    private function getTimeout(): int
    {
        return max(
            1,
            (int) config(
                'espay.timeout',
                30
            )
        );
    }

    /**
     * Log request QRIS.
     *
     * Tidak pernah menyimpan:
     * - API Key
     * - Password
     * - Private Key
     * - X-SIGNATURE
     */
    private function logQrisRequest(
        Payment $payment,
        $order,
        array $body,
        array $headers,
        string $jsonBody,
        string $relativeUrl,
        string $url,
    ): void {
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

        Log::info('ESPay QRIS Request', [
            'order_number' =>
            $order->order_number,

            'payment_number' =>
            $payment->payment_number,

            'partner_reference_no' =>
            $body['partnerReferenceNo'] ?? null,

            'external_id' =>
            $headers['X-EXTERNAL-ID'] ?? null,

            'merchant_code' =>
            config('espay.merchant_code'),

            'product_code' =>
            data_get(
                $body,
                'additionalInfo.productCode'
            ),

            'amount' =>
            data_get(
                $body,
                'amount.value'
            ),

            'url' => $url,

            'timestamp' =>
            $headers['X-TIMESTAMP'] ?? null,
        ]);
    }

    /**
     * Log response lengkap dari Espay.
     */
    private function logQrisFullResponse(
        Payment $payment,
        Response $response,
    ): void {
        Log::info('ESPay QRIS FULL RESPONSE', [
            'payment_number' =>
            $payment->payment_number,

            'http_status' =>
            $response->status(),

            'headers' =>
            $response->headers(),

            'body' =>
            $response->body(),

            'json' =>
            $response->json(),
        ]);
    }
}
