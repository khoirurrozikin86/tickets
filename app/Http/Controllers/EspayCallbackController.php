<?php

namespace App\Http\Controllers;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use App\Domain\Tickets\Actions\GenerateTicketsForOrderAction;
use App\Domain\Invoices\Actions\CreateInvoiceAction;
use App\Domain\Notifications\Actions\SendOrderTicketEmailAction;
use App\Domain\Discounts\Actions\ConsumeDiscountAction;

class EspayCallbackController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
        private readonly GenerateTicketsForOrderAction $generateTicketsForOrderAction,
        private readonly CreateInvoiceAction $createInvoiceAction,
        private readonly SendOrderTicketEmailAction $sendOrderTicketEmailAction,
        private readonly ConsumeDiscountAction $consumeDiscountAction,
    ) {}

    /**
     * ESPay Inquiry Callback.
     */
    public function inquiry(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        $this->logCallback('INQUIRY', $request, $data);

        $virtualAccountNo = $data['virtualAccountNo'] ?? null;

        if (! $virtualAccountNo) {
            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: null,
                description: 'ESPay inquiry gagal: Virtual Account tidak ditemukan.',
                newValues: [
                    'type' => 'INQUIRY',
                    'reason' => 'VIRTUAL_ACCOUNT_NOT_FOUND',
                ],
            );

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
            ->where(
                'order_id',
                $reference['order_id']
            )
            ->first();

        if (! $order || ! $payment) {
            Log::warning(
                'ESPay INQUIRY ORDER/PAYMENT NOT FOUND',
                [
                    'virtual_account_no' => $virtualAccountNo,
                    'order_id' => $reference['order_id'],
                    'payment_id' => $reference['payment_id'],
                ]
            );

            return $this->errorResponse(
                '4042401',
                'Virtual Account Not Found'
            );
        }

        if ($payment->status !== 'PENDING') {
            Log::warning(
                'ESPay INQUIRY PAYMENT NOT PENDING',
                [
                    'order_number' => $order->order_number,
                    'payment_number' => $payment->payment_number,
                    'payment_status' => $payment->status,
                ]
            );

            return $this->errorResponse(
                '4002402',
                'Payment is not available'
            );
        }

        if ($this->isExpired($order)) {
            Log::warning(
                'ESPay INQUIRY TRANSACTION EXPIRED',
                [
                    'order_number' => $order->order_number,
                    'payment_number' => $payment->payment_number,
                ]
            );

            return $this->errorResponse(
                '4002402',
                'Transaction Expired'
            );
        }

        return $this->inquirySuccessResponse(
            $data,
            $order,
            $payment,
            $virtualAccountNo
        );
    }

    /**
     * ESPay Payment Notification Callback.
     */
    public function payment(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        $this->logCallback(
            'PAYMENT',
            $request,
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Validate payload
        |--------------------------------------------------------------------------
        */

        if (! is_array($data) || empty($data)) {
            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: null,
                description: 'ESPay payment callback gagal: payload tidak valid.',
                newValues: [
                    'reason' => 'INVALID_REQUEST',
                ],
            );

            return $this->errorResponse(
                '4002500',
                'Invalid Request'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate signature
        |--------------------------------------------------------------------------
        */

        if (! $this->verifySignature($request)) {
            Log::warning(
                'ESPay PAYMENT INVALID SIGNATURE'
            );

            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: null,
                description: 'ESPay payment callback ditolak karena signature tidak valid.',
                newValues: [
                    'reason' => 'INVALID_SIGNATURE',
                    'partner_id' =>
                    $request->header('X-PARTNER-ID'),
                    'external_id' =>
                    $request->header('X-EXTERNAL-ID'),
                ],
            );

            return $this->errorResponse(
                '4012500',
                'Unauthorized'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate partner
        |--------------------------------------------------------------------------
        */

        if (! $this->isValidPartner($request)) {
            Log::warning(
                'ESPay PAYMENT INVALID PARTNER ID',
                [
                    'partner_id' =>
                    $request->header('X-PARTNER-ID'),
                ]
            );

            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: null,
                description: 'ESPay payment callback ditolak karena partner ID tidak valid.',
                newValues: [
                    'reason' => 'INVALID_PARTNER',
                    'partner_id' =>
                    $request->header('X-PARTNER-ID'),
                ],
            );

            return $this->errorResponse(
                '4012500',
                'Unauthorized'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Extract callback data
        |--------------------------------------------------------------------------
        */

        $virtualAccountNo =
            $data['virtualAccountNo']
            ?? null;

        $paymentRequestId =
            $data['paymentRequestId']
            ?? null;

        $paidAmount =
            $data['paidAmount']['value']
            ?? null;

        $totalAmount =
            $data['totalAmount']['value']
            ?? null;

        $currency =
            $data['paidAmount']['currency']
            ?? $data['totalAmount']['currency']
            ?? 'IDR';

        $transactionStatus =
            $data['additionalInfo']['transactionStatus']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | Validate Virtual Account
        |--------------------------------------------------------------------------
        */

        if (! $virtualAccountNo) {
            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: null,
                description: 'ESPay payment gagal: Virtual Account tidak ditemukan.',
                newValues: [
                    'reason' => 'VIRTUAL_ACCOUNT_NOT_FOUND',
                    'payment_request_id' =>
                    $paymentRequestId,
                ],
            );

            return $this->errorResponse(
                '4002500',
                'Virtual Account Not Found'
            );
        }

        $reference =
            $this->parseReference(
                $virtualAccountNo
            );

        if (! $reference) {
            Log::warning(
                'ESPay PAYMENT INVALID REFERENCE',
                [
                    'virtual_account_no' =>
                    $virtualAccountNo,
                ]
            );

            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: null,
                description: 'ESPay payment gagal: reference Virtual Account tidak valid.',
                newValues: [
                    'reason' => 'INVALID_REFERENCE',
                    'virtual_account_no' =>
                    $virtualAccountNo,
                    'payment_request_id' =>
                    $paymentRequestId,
                ],
            );

            return $this->errorResponse(
                '4002500',
                'Invalid Reference'
            );
        }

        $payment =
            $this->findPaymentByReference(
                $reference
            );

        /*
        |--------------------------------------------------------------------------
        | Validate transaction status
        |--------------------------------------------------------------------------
        */

        if ($transactionStatus !== 'S') {
            Log::warning(
                'ESPay PAYMENT NOT SUCCESS',
                [
                    'virtual_account_no' =>
                    $virtualAccountNo,
                    'transaction_status' =>
                    $transactionStatus,
                    'trx_id' =>
                    $data['trxId'] ?? null,
                ]
            );

            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: $payment,
                description: 'Pembayaran ESPay tidak berhasil.',
                newValues: [
                    'transaction_status' =>
                    $transactionStatus,
                    'payment_request_id' =>
                    $paymentRequestId,
                    'trx_id' =>
                    $data['trxId'] ?? null,
                    'payment_reference' =>
                    $data['additionalInfo']['paymentRef']
                        ?? null,
                ],
            );

            return $this->errorResponse(
                '4002500',
                'Payment is not successful'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate amount
        |--------------------------------------------------------------------------
        */

        if (
            $paidAmount === null ||
            $totalAmount === null
        ) {
            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: $payment,
                description: 'Pembayaran ESPay gagal: nominal tidak valid.',
                newValues: [
                    'paid_amount' =>
                    $paidAmount,
                    'total_amount' =>
                    $totalAmount,
                    'payment_request_id' =>
                    $paymentRequestId,
                ],
            );

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
            Log::error(
                'ESPay PAYMENT AMOUNT INTERNAL MISMATCH',
                [
                    'virtual_account_no' =>
                    $virtualAccountNo,
                    'paid_amount' =>
                    $paidAmount,
                    'total_amount' =>
                    $totalAmount,
                ]
            );

            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: $payment,
                description: 'Pembayaran ESPay gagal karena nominal callback tidak sesuai.',
                newValues: [
                    'paid_amount' =>
                    $paidAmount,
                    'total_amount' =>
                    $totalAmount,
                    'payment_request_id' =>
                    $paymentRequestId,
                ],
            );

            return $this->errorResponse(
                '4002500',
                'Invalid Amount'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Process successful payment
        |--------------------------------------------------------------------------
        */

        try {
            $result =
                $this->processSuccessfulPayment(
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
            Log::error(
                'ESPay PAYMENT PROCESS FAILED',
                [
                    'virtual_account_no' =>
                    $virtualAccountNo,
                    'error' =>
                    $e->getMessage(),
                ]
            );

            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: $payment,
                description: 'Pembayaran ESPay gagal diproses.',
                newValues: [
                    'reason' =>
                    $e->getMessage(),
                    'payment_request_id' =>
                    $paymentRequestId,
                    'trx_id' =>
                    $data['trxId'] ?? null,
                ],
            );

            return $this->errorResponse(
                '4002500',
                $e->getMessage()
            );
        } catch (\Throwable $e) {
            Log::error(
                'ESPay PAYMENT CALLBACK ERROR',
                [
                    'virtual_account_no' =>
                    $virtualAccountNo,
                    'error' =>
                    $e->getMessage(),
                ]
            );

            $this->auditLog(
                action: 'UPDATE',
                module: 'PAYMENT',
                model: $payment,
                description: 'ESPay payment callback mengalami internal error.',
                newValues: [
                    'reason' =>
                    $e->getMessage(),
                    'payment_request_id' =>
                    $paymentRequestId,
                    'trx_id' =>
                    $data['trxId'] ?? null,
                ],
            );

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
        return DB::transaction(
            function () use (
                $reference,
                $paymentRequestId,
                $paidAmount,
                $currency,
                $virtualAccountNo,
                $callbackPayload
            ) {
                $order = Order::query()
                    ->lockForUpdate()
                    ->find(
                        $reference['order_id']
                    );

                $payment = Payment::query()
                    ->lockForUpdate()
                    ->whereKey(
                        $reference['payment_id']
                    )
                    ->where(
                        'order_id',
                        $reference['order_id']
                    )
                    ->first();

                if (! $order || ! $payment) {
                    throw new RuntimeException(
                        'Order atau payment tidak ditemukan.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Idempotency
                |--------------------------------------------------------------------------
                */

                if ($payment->status === 'PAID') {
                    Log::info(
                        'ESPay PAYMENT ALREADY PAID',
                        [
                            'order_number' =>
                            $order->order_number,
                            'payment_number' =>
                            $payment->payment_number,
                            'payment_request_id' =>
                            $paymentRequestId,
                        ]
                    );

                    $this->auditLog(
                        action: 'UPDATE',
                        module: 'PAYMENT',
                        model: $payment,
                        description: "Callback ESPay diterima kembali untuk payment {$payment->payment_number} yang sudah PAID.",
                        newValues: [
                            'status' => 'PAID',
                            'duplicate_callback' => true,
                            'payment_request_id' =>
                            $paymentRequestId,
                        ],
                    );

                    return [
                        'order' =>
                        $order,
                        'payment' =>
                        $payment,
                        'already_paid' =>
                        true,
                    ];
                }

                /*
                |--------------------------------------------------------------------------
                | Cancelled / expired
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $payment->status,
                        [
                            'CANCELLED',
                            'EXPIRED',
                        ],
                        true
                    )
                ) {
                    $this->auditLog(
                        action: 'UPDATE',
                        module: 'PAYMENT',
                        model: $payment,
                        description: "Pembayaran ditolak karena payment {$payment->payment_number} berstatus {$payment->status}.",
                        newValues: [
                            'status' =>
                            $payment->status,
                            'payment_request_id' =>
                            $paymentRequestId,
                        ],
                    );

                    throw new RuntimeException(
                        'Payment sudah tidak dapat diproses.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Validate amount
                |--------------------------------------------------------------------------
                */

                $expectedAmount =
                    round(
                        (float) $payment->amount,
                        2
                    );

                $receivedAmount =
                    round(
                        (float) $paidAmount,
                        2
                    );

                if (
                    abs(
                        $receivedAmount -
                            $expectedAmount
                    ) > 0.01
                ) {
                    Log::error(
                        'ESPay PAYMENT AMOUNT MISMATCH',
                        [
                            'order_number' =>
                            $order->order_number,
                            'payment_number' =>
                            $payment->payment_number,
                            'expected_amount' =>
                            $expectedAmount,
                            'received_amount' =>
                            $receivedAmount,
                        ]
                    );

                    $this->auditLog(
                        action: 'UPDATE',
                        module: 'PAYMENT',
                        model: $payment,
                        description: "Pembayaran {$payment->payment_number} gagal karena nominal tidak sesuai.",
                        newValues: [
                            'expected_amount' =>
                            $expectedAmount,
                            'received_amount' =>
                            $receivedAmount,
                        ],
                    );

                    throw new RuntimeException(
                        'Payment amount tidak sesuai.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Validate currency
                |--------------------------------------------------------------------------
                */

                $expectedCurrency =
                    strtoupper(
                        $order->currency ?? 'IDR'
                    );

                if (
                    strtoupper($currency) !==
                    $expectedCurrency
                ) {
                    Log::error(
                        'ESPay PAYMENT CURRENCY MISMATCH',
                        [
                            'order_number' =>
                            $order->order_number,
                            'payment_number' =>
                            $payment->payment_number,
                            'expected_currency' =>
                            $expectedCurrency,
                            'received_currency' =>
                            $currency,
                        ]
                    );

                    $this->auditLog(
                        action: 'UPDATE',
                        module: 'PAYMENT',
                        model: $payment,
                        description: "Pembayaran {$payment->payment_number} gagal karena currency tidak sesuai.",
                        newValues: [
                            'expected_currency' =>
                            $expectedCurrency,
                            'received_currency' =>
                            $currency,
                        ],
                    );

                    throw new RuntimeException(
                        'Currency payment tidak sesuai.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Save payment
                |--------------------------------------------------------------------------
                */

                $oldPaymentValues = [
                    'status' =>
                    $payment->status,
                    'paid_at' =>
                    $payment->paid_at,
                    'amount' =>
                    $payment->amount,
                ];

                $paidAt = now();

                $payment->update([
                    'status' =>
                    'PAID',

                    'paid_at' =>
                    $paidAt,

                    'gateway_transaction_id' =>
                    $paymentRequestId,

                    'callback_payload' =>
                    $callbackPayload,

                    'metadata' =>
                    array_merge(
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
                |--------------------------------------------------------------------------
                | Save order
                |--------------------------------------------------------------------------
                */

                $oldOrderValues = [
                    'status' =>
                    $order->status,
                    'payment_status' =>
                    $order->payment_status,
                    'paid_at' =>
                    $order->paid_at,
                    'completed_at' =>
                    $order->completed_at,
                ];

                $order->update([
                    'status' =>
                    'PAID',

                    'payment_status' =>
                    'PAID',

                    'paid_at' =>
                    $paidAt,

                    'completed_at' =>
                    $paidAt,
                ]);




                // Generate ticket
                $tickets = $this->generateTicketsForOrderAction->execute($order);

                // Generate invoice
                $this->createInvoiceAction->execute($order);

                // Generate email   
                $this->sendOrderTicketEmailAction->execute($order);


                /*
|--------------------------------------------------------------------------
| Consume Discount
|--------------------------------------------------------------------------
*/

                $this->consumeDiscountAction->execute($order);

                /*
                |--------------------------------------------------------------------------
                | Audit Payment SUCCESS
                |--------------------------------------------------------------------------
                */

                $this->auditLog(
                    action: 'UPDATE',
                    module: 'PAYMENT',
                    model: $payment,
                    description: "Pembayaran {$payment->payment_number} berhasil melalui ESPay.",
                    oldValues: $oldPaymentValues,
                    newValues: [
                        'status' =>
                        'PAID',
                        'amount' =>
                        $expectedAmount,
                        'currency' =>
                        $currency,
                        'gateway_transaction_id' =>
                        $paymentRequestId,
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
                    ],
                );

                /*
                |--------------------------------------------------------------------------
                | Audit Order SUCCESS
                |--------------------------------------------------------------------------
                */

                $this->auditLog(
                    action: 'UPDATE',
                    module: 'ORDER',
                    model: $order,
                    description: "Order {$order->order_number} berhasil dibayar melalui ESPay.",
                    oldValues: $oldOrderValues,
                    newValues: [
                        'status' =>
                        'PAID',
                        'payment_status' =>
                        'PAID',
                        'paid_at' =>
                        $paidAt,
                        'completed_at' =>
                        $paidAt,
                    ],
                );

                Log::info(
                    'ESPay PAYMENT SUCCESS',
                    [
                        'order_number' =>
                        $order->order_number,
                        'payment_number' =>
                        $payment->payment_number,
                        'amount' =>
                        $expectedAmount,
                        'payment_request_id' =>
                        $paymentRequestId,
                        'trx_id' =>
                        $callbackPayload['trxId']
                            ?? null,
                    ]
                );

                return [
                    'order' =>
                    $order,
                    'payment' =>
                    $payment->fresh(),
                    'already_paid' =>
                    false,
                ];
            }
        );
    }

    /**
     * Find payment from ESPay reference.
     */
    private function findPaymentByReference(
        array $reference
    ): ?Payment {
        return Payment::query()
            ->whereKey(
                $reference['payment_id']
            )
            ->where(
                'order_id',
                $reference['order_id']
            )
            ->first();
    }

    /**
     * Verify ESPay asymmetric signature.
     */
    private function verifySignature(
        Request $request
    ): bool {
        $signature =
            $request->header('X-SIGNATURE');

        $timestamp =
            $request->header('X-TIMESTAMP');

        if (
            ! $signature ||
            ! $timestamp
        ) {
            Log::warning(
                'ESPay PAYMENT SIGNATURE HEADER MISSING'
            );

            return false;
        }

        $rawBody =
            $request->getContent();

        if ($rawBody === '') {
            Log::warning(
                'ESPay PAYMENT EMPTY BODY'
            );

            return false;
        }

        $bodyHash =
            strtolower(
                hash(
                    'sha256',
                    $rawBody
                )
            );

        $relativeUrl =
            '/api/espay/payment';

        $stringToSign =
            sprintf(
                'POST:%s:%s:%s',
                $relativeUrl,
                $bodyHash,
                $timestamp
            );

        $publicKeyPath =
            config(
                'espay.public_key_path',
                'storage/app/private/espay/public.key'
            );

        if (
            ! str_starts_with(
                $publicKeyPath,
                '/'
            )
        ) {
            $publicKeyPath =
                base_path(
                    $publicKeyPath
                );
        }

        if (
            ! is_file(
                $publicKeyPath
            )
        ) {
            Log::error(
                'ESPay PAYMENT PUBLIC KEY NOT FOUND',
                [
                    'path' =>
                    $publicKeyPath,
                ]
            );

            return false;
        }

        $publicKey =
            file_get_contents(
                $publicKeyPath
            );

        if ($publicKey === false) {
            Log::error(
                'ESPay PAYMENT PUBLIC KEY READ FAILED',
                [
                    'path' =>
                    $publicKeyPath,
                ]
            );

            return false;
        }

        $decodedSignature =
            base64_decode(
                $signature,
                true
            );

        if (
            $decodedSignature === false
        ) {
            Log::warning(
                'ESPay PAYMENT SIGNATURE BASE64 INVALID'
            );

            return false;
        }

        $result =
            openssl_verify(
                $stringToSign,
                $decodedSignature,
                $publicKey,
                OPENSSL_ALGO_SHA256
            );

        Log::info(
            'ESPay PAYMENT SIGNATURE VERIFY',
            [
                'result' =>
                $result,
                'timestamp' =>
                $timestamp,
                'relative_url' =>
                $relativeUrl,
                'body_hash' =>
                $bodyHash,
                'raw_body_length' =>
                strlen($rawBody),
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
            $request->header(
                'X-PARTNER-ID'
            );

        $merchantCode =
            config(
                'espay.merchant_code'
            );

        if (
            ! $partnerId ||
            ! $merchantCode
        ) {
            return false;
        }

        return hash_equals(
            (string) $merchantCode,
            (string) $partnerId
        );
    }

    /**
     * Parse ESPay virtual account/reference.
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
            'order_id' =>
            (int) $matches[1],

            'payment_id' =>
            (int) $matches[2],
        ];
    }

    /**
     * Check order expiration.
     */
    private function isExpired(
        Order $order
    ): bool {
        return $order->expires_at !== null
            && $order->expires_at->isPast();
    }

    /**
     * Inquiry success response.
     */
    private function inquirySuccessResponse(
        array $data,
        Order $order,
        Payment $payment,
        string $virtualAccountNo
    ): JsonResponse {
        $partnerServiceId =
            $data['partnerServiceId']
            ?? ' Espay';

        $customerNo =
            $data['customerNo']
            ?? config(
                'espay.merchant_code'
            );

        $inquiryRequestId =
            $data['inquiryRequestId']
            ?? null;

        return response()->json([
            'responseCode' =>
            '2002400',

            'responseMessage' =>
            'Success',

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
                    'value' =>
                    number_format(
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
                    ->format(
                        'Y-m-d\TH:i:sP'
                    ),
            ],
        ]);
    }

    /**
     * Payment success response.
     */
    private function paymentSuccessResponse(
        Order $order,
        Payment $payment,
        string $virtualAccountNo,
        ?string $paymentRequestId
    ): JsonResponse {
        return response()->json([
            'responseCode' =>
            '2002500',

            'responseMessage' =>
            'Success',

            'virtualAccountData' => [
                'partnerServiceId' =>
                ' Espay',

                'customerNo' =>
                config(
                    'espay.merchant_code'
                ),

                'virtualAccountNo' =>
                $virtualAccountNo,

                'virtualAccountName' =>
                $order->customer_name,

                'paymentRequestId' =>
                $paymentRequestId,

                'totalAmount' => [
                    'value' =>
                    number_format(
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
                $request->header(
                    'Content-Type'
                ),

                'partner_id' =>
                $request->header(
                    'X-PARTNER-ID'
                ),

                'external_id' =>
                $request->header(
                    'X-EXTERNAL-ID'
                ),

                'channel_id' =>
                $request->header(
                    'CHANNEL-ID'
                ),

                'timestamp' =>
                $request->header(
                    'X-TIMESTAMP'
                ),

                'body' =>
                $request->getContent(),

                'data' =>
                $data,
            ]
        );
    }

    /**
     * Write Audit Trail safely.
     *
     * Audit failure tidak boleh membuat callback
     * ESPay menjadi gagal.
     */
    private function auditLog(
        string $action,
        string $module,
        ?Model $model,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): void {
        try {
            $this->auditLogService->log(
                action: $action,
                module: $module,
                model: $model,
                description: $description,
                oldValues: $oldValues,
                newValues: $newValues,
            );
        } catch (\Throwable $e) {
            Log::error(
                'ESPay AUDIT LOG FAILED',
                [
                    'action' =>
                    $action,
                    'module' =>
                    $module,
                    'description' =>
                    $description,
                    'error' =>
                    $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Standard error response.
     */
    private function errorResponse(
        string $code,
        string $message
    ): JsonResponse {
        return response()->json([
            'responseCode' =>
            $code,

            'responseMessage' =>
            $message,
        ]);
    }
}
