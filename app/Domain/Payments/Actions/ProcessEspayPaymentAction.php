<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\DTOs\EspayPaymentData;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ProcessEspayPaymentAction
{
    public function execute(EspayPaymentData $data): array
    {
        return DB::transaction(function () use ($data) {

            $payment = Payment::query()
                ->with('order')
                ->where('payment_number', $data->partnerReferenceNo)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                return [
                    'responseCode' => '4044701',
                    'responseMessage' => 'Payment not found',
                ];
            }

            /*
             * Idempotency:
             * callback Espay bisa saja dikirim lebih dari sekali.
             */
            if ($payment->status === 'PAID') {
                return [
                    'responseCode' => '2004700',
                    'responseMessage' => 'Successful',
                ];
            }

            /*
             * Validasi nominal.
             */
            if ($data->amount !== null) {
                $callbackAmount = (float) $data->amount;
                $paymentAmount = (float) $payment->amount;

                if (abs($callbackAmount - $paymentAmount) > 0.01) {
                    Log::warning('ESPay payment amount mismatch', [
                        'payment_number' => $payment->payment_number,
                        'payment_amount' => $paymentAmount,
                        'callback_amount' => $callbackAmount,
                    ]);

                    return [
                        'responseCode' => '4004700',
                        'responseMessage' => 'Invalid amount',
                    ];
                }
            }

            /*
             * Simpan callback untuk audit/debug.
             */
            $payment->update([
                'status' => 'PAID',
                'paid_at' => now(),
                'callback_payload' => $data->payload,
                'gateway_reference' => $data->referenceNo
                    ?: $payment->gateway_reference,
            ]);

            /*
             * Update order.
             */
            if ($payment->order) {
                $payment->order->update([
                    'status' => 'PAID',
                    'payment_status' => 'PAID',
                    'paid_at' => now(),
                ]);
            }

            Log::info('ESPay payment processed', [
                'payment_number' => $payment->payment_number,
                'order_number' => $payment->order?->order_number,
                'amount' => $payment->amount,
                'reference_no' => $data->referenceNo,
            ]);

            return [
                'responseCode' => '2004700',
                'responseMessage' => 'Successful',
            ];
        });
    }
}
