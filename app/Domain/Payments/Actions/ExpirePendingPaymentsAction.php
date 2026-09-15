<?php

namespace App\Domain\Payments\Actions;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

final class ExpirePendingPaymentsAction
{
    public function execute(): int
    {
        return DB::transaction(function () {
            $payments = Payment::query()
                ->where('status', 'PENDING')
                ->whereNotNull('expired_at')
                ->where('expired_at', '<=', now())
                ->with('order')
                ->lockForUpdate()
                ->get();

            $expiredCount = 0;

            foreach ($payments as $payment) {
                // Pastikan payment masih PENDING
                if ($payment->status !== 'PENDING') {
                    continue;
                }

                $payment->update([
                    'status' => 'EXPIRED',
                ]);

                // Order hanya di-expire jika masih PENDING
                $order = $payment->order;

                if (
                    $order &&
                    $order->status === 'PENDING'
                ) {
                    $order->update([
                        'status' => 'EXPIRED',
                        'payment_status' => 'EXPIRED',
                    ]);
                }

                $expiredCount++;
            }

            return $expiredCount;
        });
    }
}
