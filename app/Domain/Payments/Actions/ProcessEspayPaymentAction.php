<?php

namespace App\Domain\Payments\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Domain\Payments\DTOs\EspayPaymentData;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ProcessEspayPaymentAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(
        EspayPaymentData $data,
        int $orderId,
        int $paymentId,
    ): array {
        return DB::transaction(
            function () use (
                $data,
                $orderId,
                $paymentId
            ) {
                $order = Order::query()
                    ->lockForUpdate()
                    ->find($orderId);

                $payment = Payment::query()
                    ->lockForUpdate()
                    ->whereKey($paymentId)
                    ->where(
                        'order_id',
                        $orderId
                    )
                    ->first();

                if (! $order || ! $payment) {
                    return [
                        'success' => false,

                        'responseCode' =>
                            '4044701',

                        'responseMessage' =>
                            'Order or payment not found',
                    ];
                }

                /*
                 * Idempotency.
                 */
                if ($payment->status === 'PAID') {
                    return [
                        'success' => true,

                        'already_paid' => true,

                        'order' => $order,

                        'payment' => $payment,
                    ];
                }

                /*
                 * Status callback.
                 */
                if (
                    $data->transactionStatus !== 'S'
                ) {
                    return [
                        'success' => false,

                        'responseCode' =>
                            '4002500',

                        'responseMessage' =>
                            'Payment is not successful',
                    ];
                }

                /*
                 * Amount wajib ada.
                 */
                if (
                    $data->paidAmount === null ||
                    $data->totalAmount === null
                ) {
                    return [
                        'success' => false,

                        'responseCode' =>
                            '4002500',

                        'responseMessage' =>
                            'Invalid Amount',
                    ];
                }

                $paidAmount =
                    round(
                        (float)
                            $data->paidAmount,
                        2
                    );

                $totalAmount =
                    round(
                        (float)
                            $data->totalAmount,
                        2
                    );

                $expectedAmount =
                    round(
                        (float)
                            $payment->amount,
                        2
                    );

                /*
                 * paidAmount === totalAmount
                 */
                if (
                    abs(
                        $paidAmount -
                        $totalAmount
                    ) > 0.01
                ) {
                    return [
                        'success' => false,

                        'responseCode' =>
                            '4002500',

                        'responseMessage' =>
                            'Invalid Amount',
                    ];
                }

                /*
                 * Callback amount === DB amount
                 */
                if (
                    abs(
                        $paidAmount -
                        $expectedAmount
                    ) > 0.01
                ) {
                    Log::error(
                        'ESPay PAYMENT AMOUNT MISMATCH',
                        [
                            'order_id' =>
                                $order->id,

                            'payment_id' =>
                                $payment->id,

                            'expected' =>
                                $expectedAmount,

                            'received' =>
                                $paidAmount,
                        ]
                    );

                    return [
                        'success' => false,

                        'responseCode' =>
                            '4002500',

                        'responseMessage' =>
                            'Payment amount tidak sesuai',
                    ];
                }

                /*
                 * Currency.
                 */
                $expectedCurrency =
                    strtoupper(
                        $order->currency ?? 'IDR'
                    );

                $receivedCurrency =
                    strtoupper(
                        $data->currency
                    );

                if (
                    $expectedCurrency !==
                    $receivedCurrency
                ) {
                    return [
                        'success' => false,

                        'responseCode' =>
                            '4002500',

                        'responseMessage' =>
                            'Currency payment tidak sesuai',
                    ];
                }

                /*
                 * Simpan old values SEBELUM update.
                 */
                $oldPaymentValues = [
                    'status' =>
                        $payment->status,

                    'paid_at' =>
                        $payment->paid_at,
                ];

                $oldOrderValues = [
                    'status' =>
                        $order->status,

                    'payment_status' =>
                        $order->payment_status,

                    'paid_at' =>
                        $order->paid_at,
                ];

                $paidAt = now();

                $metadata =
                    is_array($payment->metadata)
                        ? $payment->metadata
                        : [];

                $metadata = array_merge(
                    $metadata,
                    [
                        'payment_request_id' =>
                            $data->paymentRequestId,

                        'virtual_account_no' =>
                            $data->virtualAccountNo,

                        'trx_id' =>
                            $data->trxId,

                        'payment_reference' =>
                            $data->paymentRef,

                        'rrn' =>
                            $data->rrn,

                        'approval_code' =>
                            $data->approvalCode,

                        'transaction_status' =>
                            $data->transactionStatus,

                        'currency' =>
                            $receivedCurrency,

                        'callback_received_at' =>
                            $paidAt->toIso8601String(),
                    ]
                );

                /*
                 * PAYMENT → PAID
                 */
                $payment->update([
                    'status' =>
                        'PAID',

                    'paid_at' =>
                        $paidAt,

                    'gateway_transaction_id' =>
                        $data->trxId
                        ?: $data->paymentRequestId,

                    'gateway_reference' =>
                        $data->paymentRef
                        ?: $payment->gateway_reference,

                    'callback_payload' =>
                        $data->payload,

                    'metadata' =>
                        $metadata,
                ]);

                /*
                 * ORDER → PAID
                 */
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

                /*
                 * AUDIT PAYMENT
                 */
                $this->auditLogService->log(
                    action: 'PAID',

                    module: 'PAYMENT',

                    model: $payment,

                    description:
                        'Payment marked as PAID by ESPay callback.',

                    oldValues:
                        $oldPaymentValues,

                    newValues: [
                        'status' =>
                            'PAID',

                        'paid_at' =>
                            $paidAt
                                ->toDateTimeString(),

                        'gateway_reference' =>
                            $payment
                                ->gateway_reference,

                        'gateway_transaction_id' =>
                            $payment
                                ->gateway_transaction_id,
                    ],
                );

                /*
                 * AUDIT ORDER
                 */
                $this->auditLogService->log(
                    action: 'PAID',

                    module: 'ORDER',

                    model: $order,

                    description:
                        'Order marked as PAID after successful ESPay payment.',

                    oldValues:
                        $oldOrderValues,

                    newValues: [
                        'status' =>
                            'PAID',

                        'payment_status' =>
                            'PAID',

                        'paid_at' =>
                            $paidAt
                                ->toDateTimeString(),

                        'completed_at' =>
                            $paidAt
                                ->toDateTimeString(),
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

                        'currency' =>
                            $receivedCurrency,

                        'trx_id' =>
                            $data->trxId,
                    ]
                );

                return [
                    'success' => true,

                    'already_paid' => false,

                    'order' => $order,

                    'payment' =>
                        $payment->fresh(),
                ];
            }
        );
    }
}