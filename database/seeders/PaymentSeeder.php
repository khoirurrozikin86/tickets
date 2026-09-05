<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::query()
            ->orderBy('id')
            ->get();

        if ($orders->isEmpty()) {
            $this->command?->warn('Tidak ada data order. Jalankan OrderSeeder terlebih dahulu.');
            return;
        }

        $statuses = [
            'PENDING',
            'PAID',
            'FAILED',
            'EXPIRED',
            'CANCELLED',
            'REFUNDED',
        ];

        foreach ($orders as $index => $order) {
            $status = $statuses[$index % count($statuses)];

            $paidAt = null;
            $cancelledAt = null;

            if ($status === 'PAID' || $status === 'REFUNDED') {
                $paidAt = now()->subDays(rand(0, 5));
            }

            if ($status === 'CANCELLED') {
                $cancelledAt = now()->subDays(rand(0, 3));
            }

            $expiredAt = $status === 'EXPIRED'
                ? now()->subDays(rand(1, 5))
                : now()->addMinutes(30);

            $paymentNumber = 'PAY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));

            Payment::updateOrCreate(
                [
                    'order_id' => $order->id,
                ],
                [
                    'payment_number' => $paymentNumber,
                    'gateway' => 'ESPAY',
                    'payment_method' => 'QRIS',
                    'payment_channel' => 'QRIS',
                    'gateway_reference' => 'ESPAY-' . strtoupper(Str::random(12)),
                    'gateway_transaction_id' => $status === 'PENDING'
                        ? null
                        : 'TRX-' . strtoupper(Str::random(12)),
                    'amount' => $order->total_amount,
                    'currency' => 'IDR',
                    'status' => $status,
                    'expired_at' => $expiredAt,
                    'paid_at' => $paidAt,
                    'cancelled_at' => $cancelledAt,
                    'payment_url' => null,
                    'qr_code' => null,
                    'callback_payload' => [
                        'source' => 'SEEDER',
                        'status' => $status,
                    ],
                    'metadata' => [
                        'environment' => 'development',
                        'seeded' => true,
                    ],
                    'created_at' => now()->subDays(rand(0, 7)),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command?->info(
            'PaymentSeeder berhasil membuat/update ' . $orders->count() . ' payment.'
        );
    }
}
