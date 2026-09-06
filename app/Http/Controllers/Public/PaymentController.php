<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function show(Request $request): Response
    {
        $token = $request->query('order');

        if (!$token) {
            abort(404, 'Order token tidak ditemukan.');
        }

        $order = Order::query()
            ->with([
                'items',
                'payments',
            ])
            ->where('order_token', $token)
            ->firstOrFail();

        $payment = $order->payments->first();

        $settings = SiteSetting::query()
            ->where('is_active', true)
            ->get(['key', 'value'])
            ->mapWithKeys(function ($setting) {
                return [
                    $setting->key => $setting->value,
                ];
            })
            ->toArray();

        return Inertia::render('Public/Payment', [
            'order' => [
                'id' => $order->id,

                'orderNumber' => $order->order_number,

                'customerName' => $order->customer_name,
                'customerEmail' => $order->customer_email,
                'customerPhone' => $order->customer_phone,

                'subtotal' => (float) $order->subtotal,
                'discountAmount' => (float) $order->discount_amount,
                'totalAmount' => (float) $order->total_amount,

                'currency' => $order->currency,

                'status' => $order->status,
                'paymentStatus' => $order->payment_status,

                'expiresAt' => $order->expires_at?->toISOString(),

                'items' => $order->items->map(function ($item) {
                    return [
                        'productName' => $item->product_name,
                        'visitDate' => $item->visit_date,
                        'quantity' => (int) $item->quantity,
                        'unitPrice' => (float) $item->unit_price,
                        'subtotal' => (float) $item->subtotal,
                    ];
                })->values()->toArray(),

                'payment' => $payment
                    ? [
                        'paymentNumber' => $payment->payment_number,
                        'amount' => (float) $payment->amount,
                        'method' => $payment->payment_method,
                        'channel' => $payment->payment_channel,
                        'status' => $payment->status,
                        'expiredAt' => $payment->expired_at?->toISOString(),
                        'paymentUrl' => $payment->payment_url,
                        'qrCode' => $payment->qr_code,
                    ]
                    : null,
            ],

            'settings' => $settings,
        ]);
    }
}
