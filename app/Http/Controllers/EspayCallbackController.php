<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class EspayCallbackController extends Controller
{
    /**
     * ESPay Inquiry Callback.
     *
     * Dipanggil ESPay untuk memastikan transaksi
     * masih valid sebelum pembayaran QRIS diproses.
     */
    public function inquiry(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        $this->logCallback('INQUIRY', $request, $data);

        $virtualAccountNo = $data['virtualAccountNo'] ?? null;

        if (! $virtualAccountNo) {
            return $this->errorResponse(
                '4002402',
                'Invalid Virtual Account'
            );
        }

        $reference = $this->parseReference($virtualAccountNo);

        if (! $reference) {
            Log::warning('ESPay INQUIRY INVALID REFERENCE', [
                'virtual_account_no' => $virtualAccountNo,
            ]);

            return $this->errorResponse(
                '4002402',
                'Invalid Virtual Account'
            );
        }

        $order = Order::query()
            ->find($reference['order_id']);

        $payment = Payment::query()
            ->whereKey($reference['payment_id'])
            ->where('order_id', $reference['order_id'])
            ->first();

        if (! $order || ! $payment) {
            Log::warning('ESPay INQUIRY ORDER/PAYMENT NOT FOUND', [
                'virtual_account_no' => $virtualAccountNo,
                'order_id' => $reference['order_id'],
                'payment_id' => $reference['payment_id'],
            ]);

            return $this->errorResponse(
                '4042401',
                'Virtual Account Not Found'
            );
        }

        if ($payment->status !== 'PENDING') {
            Log::warning('ESPay INQUIRY PAYMENT NOT PENDING', [
                'order_number' => $order->order_number,
                'payment_number' => $payment->payment_number,
                'payment_status' => $payment->status,
            ]);

            return $this->errorResponse(
                '4002402',
                'Payment is not available'
            );
        }

        if ($this->isExpired($order)) {
            Log::warning('ESPay INQUIRY TRANSACTION EXPIRED', [
                'order_number' => $order->order_number,
                'payment_number' => $payment->payment_number,
            ]);

            return $this->errorResponse(
                '4002402',
                'Transaction Expired'
            );
        }

        return $this->inquirySuccessResponse(
            $request,
            $data,
            $order,
            $payment,
            $virtualAccountNo
        );
    }

    /**
     * ESPay Payment Notification Callback.
     *
     * Dipanggil ESPay setelah pembayaran berhasil.
     */
    public function payment(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        $this->logCallback('PAYMENT', $request, $data);

        if (! is_array($data) || empty($data)) {
            return $this->errorResponse(
                '4002500',
                'Invalid Request'
            );
        }

        /*
         * 1. Validasi signature.
         */
        if (! $this->verifySignature($request)) {
            Log::warning('ESPay PAYMENT INVALID SIGNATURE');

            return $this->errorResponse(
                '4012500',
                'Unauthorized'
            );
        }

        /*
         * 2. Validasi partner.
         */
        if (! $this->isValidPartner($request)) {
            Log::warning('ESPay PAYMENT INVALID PARTNER ID', [
                'partner_id' => $request->header('X-PARTNER-ID'),
            ]);

            return $this->errorResponse(
                '4012500',
                'Unauthorized'
            );
        }

        /*
         * 3. Ambil data payment dari ROOT payload.
         *
         * Callback ESPay:
         *
         * {
         *   "virtualAccountNo": "...",
         *   "paymentRequestId": "...",
         *   "paidAmount": {...},
         *   "totalAmount": {...},
         *   "additionalInfo": {
         *       "transactionStatus": "S"
         *   }
         * }
         */
        $virtualAccountNo = $data['virtualAccountNo'] ?? null;

        if (! $virtualAccountNo) {
            return $this->errorResponse(
                '4002500',
                'Virtual Account Not Found'
            );
        }

        $reference = $this->parseReference($virtualAccountNo);

        if (! $reference) {
            Log::warning('ESPay PAYMENT INVALID REFERENCE', [
                'virtual_account_no' => $virtualAccountNo,
            ]);

            return $this->errorResponse(
                '4002500',
                'Invalid Reference'
            );
        }

        $paymentRequestId = $data['paymentRequestId'] ?? null;

        $paidAmount = $data['paidAmount']['value'] ?? null;

        $totalAmount = $data['totalAmount']['value'] ?? null;

        $currency = $data['paidAmount']['currency']
            ?? $data['totalAmount']['currency']
            ?? 'IDR';

        $transactionStatus =
            $data['additionalInfo']['transactionStatus']
            ?? null;

        /*
         * 4. Validasi status transaksi.
         */
        if ($transactionStatus !== 'S') {
            Log::warning('ESPay PAYMENT NOT SUCCESS', [
                'virtual_account_no' => $virtualAccountNo,
                'transaction_status' => $transactionStatus,
                'trx_id' => $data['trxId'] ?? null,
            ]);

            return $this->errorResponse(
                '4002500',
                'Payment is not successful'
            );
        }

        /*
         * 5. Validasi nominal.
         */
        if ($paidAmount === null || $totalAmount === null) {
            return $this->errorResponse(
                '4002500',
                'Invalid Amount'
            );
        }

        if (
            abs(
                (float) $paidAmount -
                (float) $totalAmount
            ) > 0.01
        ) {
            Log::error('ESPay PAYMENT AMOUNT INTERNAL MISMATCH', [
                'virtual_account_no' => $virtualAccountNo,
                'paid_amount' => $paidAmount,
                'total_amount' => $totalAmount,
            ]);

            return $this->errorResponse(
                '4002500',
                'Invalid Amount'
            );
        }

        try {
            $result = $this->processSuccessfulPayment(
                reference: $reference,
                paymentRequestId: $paymentRequestId,
                paidAmount: $paidAmount,
                currency: $currency,
                virtualAccountNo: $virtualAccountNo,
                callbackPayload: $data
            );

            return $this->paymentSuccessResponse(
                $result['order'],
                $result['payment'],
                $virtualAccountNo,
                $paymentRequestId
            );
        } catch (RuntimeException $e) {
            Log::error('ESPay PAYMENT PROCESS FAILED', [
                'virtual_account_no' => $virtualAccountNo,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse(
                '4002500',
                $e->getMessage()
            );
        } catch (\Throwable $e) {
            Log::error('ESPay PAYMENT CALLBACK ERROR', [
                'virtual_account_no' => $virtualAccountNo,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse(
                '5002500',
                'Internal Server Error'
            );
        }
    }

    /**
     * Process successful payment atomically.
     */
    private function processSuccessfulPayment(
        array $reference,
        ?string $paymentRequestId,
        string|float|int $paidAmount,
        string $currency,
        string $virtualAccountNo,
        array $callbackPayload
    ): array {
        return DB::transaction(function () use (
            $reference,
            $paymentRequestId,
            $paidAmount,
            $currency,
            $virtualAccountNo,
            $callbackPayload
        ) {
            $order = Order::query()
                ->lockForUpdate()
                ->find($reference['order_id']);

            $payment = Payment::query()
                ->lockForUpdate()
                ->whereKey($reference['payment_id'])
                ->where('order_id', $reference['order_id'])
                ->first();

            if (! $order || ! $payment) {
                throw new RuntimeException(
                    'Order atau payment tidak ditemukan.'
                );
            }

            /*
             * Idempotency.
             *
             * Jika callback yang sama dikirim ulang
             * setelah payment sudah PAID, jangan proses ulang.
             */
            if ($payment->status === 'PAID') {
                Log::info('ESPay PAYMENT ALREADY PAID', [
                    'order_number' => $order->order_number,
                    'payment_number' => $payment->payment_number,
                    'payment_request_id' => $paymentRequestId,
                ]);

                return [
                    'order' => $order,
                    'payment' => $payment,
                    'already_paid' => true,
                ];
            }

            /*
             * Payment yang sudah dibatalkan/expired
             * tidak boleh dibayar.
             */
            if (
                in_array(
                    $payment->status,
                    ['CANCELLED', 'EXPIRED'],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Payment sudah tidak dapat diproses.'
                );
            }

            /*
             * Validasi amount terhadap database.
             */
            $expectedAmount = round(
                (float) $payment->amount,
                2
            );

            $receivedAmount = round(
                (float) $paidAmount,
                2
            );

            if (
                abs(
                    $receivedAmount -
                    $expectedAmount
                ) > 0.01
            ) {
                Log::error('ESPay PAYMENT AMOUNT MISMATCH', [
                    'order_number' => $order->order_number,
                    'payment_number' => $payment->payment_number,
                    'expected_amount' => $expectedAmount,
                    'received_amount' => $receivedAmount,
                ]);

                throw new RuntimeException(
                    'Payment amount tidak sesuai.'
                );
            }

            /*
             * Validasi currency.
             */
            $expectedCurrency =
                strtoupper(
                    $order->currency ?? 'IDR'
                );

            if (
                strtoupper($currency) !==
                $expectedCurrency
            ) {
                Log::error('ESPay PAYMENT CURRENCY MISMATCH', [
                    'order_number' => $order->order_number,
                    'expected_currency' => $expectedCurrency,
                    'received_currency' => $currency,
                ]);

                throw new RuntimeException(
                    'Currency payment tidak sesuai.'
                );
            }

            $paidAt = now();

            /*
             * Update payment.
             */
            $payment->update([
                'status' => 'PAID',

                'paid_at' => $paidAt,

                'gateway_transaction_id' =>
                    $paymentRequestId,

                'callback_payload' =>
                    $callbackPayload,

                'metadata' => array_merge(
                    $payment->metadata ?? [],
                    [
                        'payment_request_id' =>
                            $paymentRequestId,

                        'virtual_account_no' =>
                            $virtualAccountNo,

                        'trx_id' =>
                            $callbackPayload['trxId']
                            ?? null,

                        'payment_reference' =>
                            $callbackPayload['additionalInfo']['paymentRef']
                            ?? null,

                        'rrn' =>
                            $callbackPayload['additionalInfo']['rrn']
                            ?? null,

                        'approval_code' =>
                            $callbackPayload['additionalInfo']['approvalCode']
                            ?? null,

                        'transaction_status' =>
                            $callbackPayload['additionalInfo']['transactionStatus']
                            ?? null,

                        'currency' =>
                            $currency,

                        'callback_received_at' =>
                            $paidAt->toIso8601String(),
                    ]
                ),
            ]);

            /*
             * Update order.
             */
            $order->update([
                'status' => 'PAID',

                'payment_status' => 'PAID',

                'paid_at' => $paidAt,

                'completed_at' => $paidAt,
            ]);

            Log::info('ESPay PAYMENT SUCCESS', [
                'order_number' => $order->order_number,
                'payment_number' => $payment->payment_number,
                'amount' => $expectedAmount,
                'payment_request_id' => $paymentRequestId,
                'trx_id' => $callbackPayload['trxId'] ?? null,
            ]);

            return [
                'order' => $order,
                'payment' => $payment->fresh(),
                'already_paid' => false,
            ];
        });
    }

    /**
     * Verify ESPay asymmetric signature.
     */
    private function verifySignature(
        Request $request
    ): bool {
        $signature = $request->header('X-SIGNATURE');
        $timestamp = $request->header('X-TIMESTAMP');

        if (! $signature || ! $timestamp) {
            Log::warning(
                'ESPay PAYMENT SIGNATURE HEADER MISSING'
            );

            return false;
        }

        /*
         * WAJIB raw body.
         *
         * Jangan menggunakan:
         *
         * json_encode($request->json()->all())
         *
         * karena hasil serialisasi dapat berbeda dari
         * body yang ditandatangani ESPay.
         */
        $rawBody = $request->getContent();

        if ($rawBody === '') {
            Log::warning(
                'ESPay PAYMENT EMPTY BODY'
            );

            return false;
        }

        $bodyHash = strtolower(
            hash('sha256', $rawBody)
        );

        /*
         * Relative URL harus sama persis dengan
         * endpoint yang didaftarkan di ESPay.
         */
        $relativeUrl = '/api/espay/payment';

        $stringToSign = sprintf(
            'POST:%s:%s:%s',
            $relativeUrl,
            $bodyHash,
            $timestamp
        );

        $publicKeyPath = config(
            'espay.public_key_path',
            'storage/app/private/espay/public.key'
        );

        /*
         * Jika path relatif, ubah menjadi absolute path.
         */
        if (! str_starts_with($publicKeyPath, '/')) {
            $publicKeyPath = base_path($publicKeyPath);
        }

        if (! is_file($publicKeyPath)) {
            Log::error(
                'ESPay PAYMENT PUBLIC KEY NOT FOUND',
                [
                    'path' => $publicKeyPath,
                ]
            );

            return false;
        }

        $publicKey = file_get_contents(
            $publicKeyPath
        );

        if ($publicKey === false) {
            Log::error(
                'ESPay PAYMENT PUBLIC KEY READ FAILED',
                [
                    'path' => $publicKeyPath,
                ]
            );

            return false;
        }

        $decodedSignature = base64_decode(
            $signature,
            true
        );

        if ($decodedSignature === false) {
            Log::warning(
                'ESPay PAYMENT SIGNATURE BASE64 INVALID'
            );

            return false;
        }


        Log::info('ESPay PAYMENT SIGNATURE HEADER', [
    'signature' => $signature,
    'signature_length' => strlen($signature),
    'timestamp' => $timestamp,
]);





        $result = openssl_verify(
            $stringToSign,
            $decodedSignature,
            $publicKey,
            OPENSSL_ALGO_SHA256
        );



        

        /*
         * Untuk sementara log data penting signature.
         *
         * Jangan log signature/body lengkap di production
         * setelah masalah selesai.
         */
        Log::info(
            'ESPay PAYMENT SIGNATURE VERIFY',
            [
                'result' => $result,

                'timestamp' => $timestamp,

                'relative_url' => $relativeUrl,

                'body_hash' => $bodyHash,

                'raw_body_length' =>
                    strlen($rawBody),

                'string_to_sign' =>
                    $stringToSign,

                'public_key_path' =>
                    $publicKeyPath,
            ]
        );

        return $result === 1;
    }

    /**
     * Validate ESPay partner ID.
     */
    private function isValidPartner(
        Request $request
    ): bool {
        $partnerId =
            $request->header('X-PARTNER-ID');

        $merchantCode =
            config('espay.merchant_code');

        if (! $partnerId || ! $merchantCode) {
            return false;
        }

        return hash_equals(
            (string) $merchantCode,
            (string) $partnerId
        );
    }

    /**
     * Parse virtual account/reference.
     *
     * Example:
     *
     * DS19P19
     */
    private function parseReference(
        string $virtualAccountNo
    ): ?array {
        if (
            ! preg_match(
                '/^DS(\d+)P(\d+)$/',
                $virtualAccountNo,
                $matches
            )
        ) {
            return null;
        }

        return [
            'order_id' => (int) $matches[1],
            'payment_id' => (int) $matches[2],
        ];
    }

    /**
     * Check order expiration.
     */
    private function isExpired(Order $order): bool
    {
        return $order->expires_at !== null
            && $order->expires_at->isPast();
    }

    /**
     * Build Inquiry success response.
     */
    private function inquirySuccessResponse(
        Request $request,
        array $data,
        Order $order,
        Payment $payment,
        string $virtualAccountNo
    ): JsonResponse {
        $partnerServiceId =
            $data['partnerServiceId'] ?? ' Espay';

        $customerNo =
            $data['customerNo']
            ?? config('espay.merchant_code');

        $inquiryRequestId =
            $data['inquiryRequestId'] ?? null;

        return response()->json([
            'responseCode' => '2002400',

            'responseMessage' => 'Success',

            'virtualAccountData' => [
                'partnerServiceId' =>
                    $partnerServiceId,

                'customerNo' =>
                    $customerNo,

                'virtualAccountNo' =>
                    $virtualAccountNo,

                'virtualAccountName' =>
                    $order->customer_name,

                'virtualAccountEmail' =>
                    $order->customer_email,

                'virtualAccountPhone' =>
                    $order->customer_phone,

                'inquiryRequestId' =>
                    $inquiryRequestId,

                'totalAmount' => [
                    'value' => number_format(
                        (float) $payment->amount,
                        2,
                        '.',
                        ''
                    ),

                    'currency' =>
                        $order->currency ?? 'IDR',
                ],

                'billDetails' => [
                    [
                        'billDescription' => [
                            'english' =>
                                'Dusun Semilir Ticket',

                            'indonesia' =>
                                'Tiket Dusun Semilir',
                        ],
                    ],
                ],
            ],

            'additionalInfo' => [
                'transactionDate' =>
                    now('Asia/Jakarta')
                        ->format('Y-m-d\TH:i:sP'),
            ],
        ]);
    }

    /**
     * Build Payment success response.
     */
    private function paymentSuccessResponse(
        Order $order,
        Payment $payment,
        string $virtualAccountNo,
        ?string $paymentRequestId
    ): JsonResponse {
        return response()->json([
            'responseCode' => '2002500',

            'responseMessage' => 'Success',

            'virtualAccountData' => [
                'partnerServiceId' =>
                    ' Espay',

                'customerNo' =>
                    config('espay.merchant_code'),

                'virtualAccountNo' =>
                    $virtualAccountNo,

                'virtualAccountName' =>
                    $order->customer_name,

                'paymentRequestId' =>
                    $paymentRequestId,

                'totalAmount' => [
                    'value' => number_format(
                        (float) $payment->amount,
                        2,
                        '.',
                        ''
                    ),

                    'currency' =>
                        $order->currency ?? 'IDR',
                ],

                'billDetails' => [
                    [
                        'billDescription' => [
                            'english' =>
                                'Dusun Semilir Ticket',

                            'indonesia' =>
                                'Tiket Dusun Semilir',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Log callback request.
     */
    private function logCallback(
        string $type,
        Request $request,
        array $data
    ): void {
        Log::info(
            "ESPay {$type} RECEIVED",
            [
                'ip' =>
                    $request->ip(),

                'method' =>
                    $request->method(),

                'content_type' =>
                    $request->header('Content-Type'),

                'partner_id' =>
                    $request->header('X-PARTNER-ID'),

                'external_id' =>
                    $request->header('X-EXTERNAL-ID'),

                'channel_id' =>
                    $request->header('CHANNEL-ID'),

                'timestamp' =>
                    $request->header('X-TIMESTAMP'),

                'body' =>
                    $request->getContent(),

                'data' =>
                    $data,
            ]
        );
    }

    /**
     * Standard error response.
     */
    private function errorResponse(
        string $code,
        string $message
    ): JsonResponse {
        return response()->json([
            'responseCode' => $code,
            'responseMessage' => $message,
        ]);
    }
}