<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()
            ->whereIn('slug', ['reguler', 'combo', 'terusan'])
            ->get()
            ->keyBy('slug');

        if ($products->isEmpty()) {
            $this->command->warn('Product belum tersedia. Jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER 1 - ACTIVE
        |--------------------------------------------------------------------------
        */

        $order = Order::updateOrCreate(
            ['order_number' => 'ORD-SEED-001'],
            [
                'order_token' => Str::random(64),
                'customer_name' => 'Budi Santoso',
                'customer_email' => 'budi@example.com',
                'customer_phone' => '081234567890',
                'subtotal' => 200000,
                'discount_id' => null,
                'discount_code' => null,
                'discount_amount' => 0,
                'total_amount' => 200000,
                'currency' => 'IDR',
                'status' => 'PAID',
                'payment_status' => 'PAID',
                'expires_at' => now()->addDay(),
                'paid_at' => now(),
                'cancelled_at' => null,
                'completed_at' => null,
                'notes' => 'Seed ticket testing',
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        $product = $products->get('reguler');

        $orderItem = OrderItem::updateOrCreate(
            [
                'order_id' => $order->id,
                'product_id' => $product->id,
            ],
            [
                'product_name' => $product->name,
                'visit_date' => now()->addDays(7)->toDateString(),
                'day_type' => 'WEEKDAY',
                'unit_price' => 40000,
                'quantity' => 3,
                'subtotal' => 120000,
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ACTIVE TICKETS
        |--------------------------------------------------------------------------
        */

        $this->createTicket(
            $order,
            $orderItem,
            $product,
            'ACTIVE'
        );

        $this->createTicket(
            $order,
            $orderItem,
            $product,
            'ACTIVE'
        );

        $this->createTicket(
            $order,
            $orderItem,
            $product,
            'ACTIVE'
        );

        /*
        |--------------------------------------------------------------------------
        | ORDER 2 - USED
        |--------------------------------------------------------------------------
        */

        $orderUsed = Order::updateOrCreate(
            ['order_number' => 'ORD-SEED-002'],
            [
                'order_token' => Str::random(64),
                'customer_name' => 'Andi Wijaya',
                'customer_email' => 'andi@example.com',
                'customer_phone' => '081298765432',
                'subtotal' => 127000,
                'discount_id' => null,
                'discount_code' => null,
                'discount_amount' => 0,
                'total_amount' => 127000,
                'currency' => 'IDR',
                'status' => 'COMPLETED',
                'payment_status' => 'PAID',
                'expires_at' => now()->addDay(),
                'paid_at' => now()->subDays(2),
                'completed_at' => now(),
                'notes' => 'Seed ticket USED',
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        $product = $products->get('combo');

        $orderItemUsed = OrderItem::updateOrCreate(
            [
                'order_id' => $orderUsed->id,
                'product_id' => $product->id,
            ],
            [
                'product_name' => $product->name,
                'visit_date' => now()->toDateString(),
                'day_type' => 'WEEKDAY',
                'unit_price' => 102000,
                'quantity' => 2,
                'subtotal' => 204000,
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        $this->createTicket(
            $orderUsed,
            $orderItemUsed,
            $product,
            'USED'
        );

        $this->createTicket(
            $orderUsed,
            $orderItemUsed,
            $product,
            'USED'
        );

        /*
        |--------------------------------------------------------------------------
        | ORDER 3 - CANCELLED
        |--------------------------------------------------------------------------
        */

        $orderCancelled = Order::updateOrCreate(
            ['order_number' => 'ORD-SEED-003'],
            [
                'order_token' => Str::random(64),
                'customer_name' => 'Siti Aminah',
                'customer_email' => 'siti@example.com',
                'customer_phone' => '081377889900',
                'subtotal' => 85000,
                'discount_id' => null,
                'discount_code' => null,
                'discount_amount' => 0,
                'total_amount' => 85000,
                'currency' => 'IDR',
                'status' => 'CANCELLED',
                'payment_status' => 'REFUNDED',
                'expires_at' => now()->subDay(),
                'paid_at' => null,
                'cancelled_at' => now(),
                'completed_at' => null,
                'notes' => 'Seed ticket CANCELLED',
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        $product = $products->get('terusan');

        $orderItemCancelled = OrderItem::updateOrCreate(
            [
                'order_id' => $orderCancelled->id,
                'product_id' => $product->id,
            ],
            [
                'product_name' => $product->name,
                'visit_date' => now()->addDays(3)->toDateString(),
                'day_type' => 'WEEKDAY',
                'unit_price' => 75000,
                'quantity' => 1,
                'subtotal' => 75000,
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        $this->createTicket(
            $orderCancelled,
            $orderItemCancelled,
            $product,
            'CANCELLED'
        );

        /*
        |--------------------------------------------------------------------------
        | ORDER 4 - EXPIRED
        |--------------------------------------------------------------------------
        */

        $orderExpired = Order::updateOrCreate(
            ['order_number' => 'ORD-SEED-004'],
            [
                'order_token' => Str::random(64),
                'customer_name' => 'Rina Lestari',
                'customer_email' => 'rina@example.com',
                'customer_phone' => '081355667788',
                'subtotal' => 50000,
                'discount_id' => null,
                'discount_code' => null,
                'discount_amount' => 0,
                'total_amount' => 50000,
                'currency' => 'IDR',
                'status' => 'EXPIRED',
                'payment_status' => 'EXPIRED',
                'expires_at' => now()->subDays(5),
                'paid_at' => null,
                'cancelled_at' => null,
                'completed_at' => null,
                'notes' => 'Seed ticket EXPIRED',
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        $product = $products->get('reguler');

        $orderItemExpired = OrderItem::updateOrCreate(
            [
                'order_id' => $orderExpired->id,
                'product_id' => $product->id,
            ],
            [
                'product_name' => $product->name,
                'visit_date' => now()->subDays(5)->toDateString(),
                'day_type' => 'WEEKDAY',
                'unit_price' => 40000,
                'quantity' => 1,
                'subtotal' => 40000,
                'metadata' => [
                    'source' => 'SEEDER',
                ],
            ]
        );

        $this->createTicket(
            $orderExpired,
            $orderItemExpired,
            $product,
            'EXPIRED'
        );

        $this->command->info('Ticket seeder berhasil.');
    }

    private function createTicket(
        Order $order,
        OrderItem $orderItem,
        Product $product,
        string $status
    ): Ticket {
        $ticketNumber = $this->generateTicketNumber();
        $token = $this->generateToken();

        $data = [
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $product->id,
            'ticket_number' => $ticketNumber,
            'token' => $token,
            'product_name' => $product->name,
            'visit_date' => $orderItem->visit_date->toDateString(),
            'status' => $status,
            'issued_at' => now()->subDays(1),
            'used_at' => $status === 'USED' ? now()->subHours(2) : null,
            'expired_at' => $status === 'EXPIRED' ? now() : null,
            'used_by' => null,
            'metadata' => [
                'source' => 'SEEDER',
            ],
        ];

        return Ticket::updateOrCreate(
            ['ticket_number' => $ticketNumber],
            $data
        );
    }

    private function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-SEED-' . strtoupper(Str::random(8));
        } while (Ticket::where('ticket_number', $number)->exists());

        return $number;
    }

    private function generateToken(): string
    {
        do {
            $token = Str::random(64);
        } while (Ticket::where('token', $token)->exists());

        return $token;
    }
}
