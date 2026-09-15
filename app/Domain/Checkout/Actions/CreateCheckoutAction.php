<?php

namespace App\Domain\Checkout\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Domain\Checkout\DTOs\CheckoutData;
use App\Domain\Checkout\Services\CheckoutPricingService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCheckoutAction
{
    public function __construct(
        private readonly CheckoutPricingService $pricingService,
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(
        CheckoutData $data,
        Product $product,
    ): array {
        return DB::transaction(function () use (
            $data,
            $product
        ) {
            /*
            |--------------------------------------------------------------------------
            | Calculate ulang di server
            |--------------------------------------------------------------------------
            */

            $pricing = $this->pricingService->calculate(
                product: $product,
                date: $data->date,
                quantity: $data->quantity,
                voucher: $data->voucher,
            );

            /*
            |--------------------------------------------------------------------------
            | Order number
            |--------------------------------------------------------------------------
            */

            $orderNumber = $this->generateOrderNumber();

            /*
            |--------------------------------------------------------------------------
            | Order token
            |--------------------------------------------------------------------------
            */

            $orderToken = Str::random(64);

            /*
            |--------------------------------------------------------------------------
            | Expiry
            |--------------------------------------------------------------------------
            */

            $expiresAt = now()->addMinutes(15);

            /*
            |--------------------------------------------------------------------------
            | Create Order
            |--------------------------------------------------------------------------
            */

            $order = Order::create([
                'order_number' => $orderNumber,

                'order_token' => $orderToken,

                'customer_name' => $data->name,

                'customer_email' => $data->email,

                'customer_phone' => $data->phone,

                'subtotal' => $pricing['subtotal'],

                'discount_id' =>
                $pricing['discount']?->id,

                'discount_code' =>
                $pricing['discount']?->code,

                'discount_amount' =>
                $pricing['discount_amount'],

                'total_amount' =>
                $pricing['total'],

                'currency' => 'IDR',

                'status' => 'PENDING',

                'payment_status' => 'PENDING',

                'expires_at' => $expiresAt,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Audit ORDER
            |--------------------------------------------------------------------------
            */

            $this->auditLogService->log(
                action: 'CREATE',
                module: 'ORDER',
                model: $order,
                description: "Pengunjung membuat order {$order->order_number}",
                newValues: [
                    'order_number' =>
                    $order->order_number,

                    'customer_name' =>
                    $order->customer_name,

                    'customer_email' =>
                    $order->customer_email,

                    'customer_phone' =>
                    $order->customer_phone,

                    'subtotal' =>
                    $order->subtotal,

                    'discount_id' =>
                    $pricing['discount']?->id,

                    'discount_code' =>
                    $order->discount_code,

                    'discount_amount' =>
                    $order->discount_amount,

                    'total_amount' =>
                    $order->total_amount,

                    'status' =>
                    $order->status,

                    'payment_status' =>
                    $order->payment_status,
                ],
            );

            /*
            |--------------------------------------------------------------------------
            | Order Item
            |--------------------------------------------------------------------------
            */

            $orderItem = OrderItem::create([
                'order_id' => $order->id,

                'product_id' => $product->id,

                'product_name' => $product->name,

                'unit_price' =>
                $pricing['unit_price'],

                'quantity' =>
                $pricing['quantity'],

                'visit_date' =>
                $pricing['date'],

                'subtotal' =>
                $pricing['subtotal'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::create([
                'order_id' => $order->id,

                'payment_number' =>
                $this->generatePaymentNumber(),

                'gateway' => 'ESPAY',

                'payment_method' => 'QRIS',

                'payment_channel' => 'QRIS',

                'amount' =>
                $pricing['total'],

                'currency' => 'IDR',

                'status' => 'PENDING',

                'expired_at' => $expiresAt,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Audit PAYMENT
            |--------------------------------------------------------------------------
            */

            $this->auditLogService->log(
                action: 'CREATE',
                module: 'PAYMENT',
                model: $payment,
                description: "Payment {$payment->payment_number} dibuat untuk order {$order->order_number}",
                newValues: [
                    'payment_number' =>
                    $payment->payment_number,

                    'order_id' =>
                    $order->id,

                    'gateway' =>
                    $payment->gateway,

                    'payment_method' =>
                    $payment->payment_method,

                    'payment_channel' =>
                    $payment->payment_channel,

                    'amount' =>
                    $payment->amount,

                    'currency' =>
                    $payment->currency,

                    'status' =>
                    $payment->status,
                ],
            );

            return [
                'order' => $order,

                'order_item' => $orderItem,

                'payment' => $payment,

                'pricing' => $pricing,
            ];
        });
    }

    private function generateOrderNumber(): string
    {
        do {
            $number =
                'ORD-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    Str::random(6)
                );
        } while (
            Order::query()
            ->where(
                'order_number',
                $number
            )
            ->exists()
        );

        return $number;
    }

    private function generatePaymentNumber(): string
    {
        do {
            $number =
                'PAY-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    Str::random(8)
                );
        } while (
            Payment::query()
            ->where(
                'payment_number',
                $number
            )
            ->exists()
        );

        return $number;
    }
}
