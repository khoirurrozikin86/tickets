<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    /**
     * Halaman pencarian reservasi.
     */
    public function index(): Response
    {
        return Inertia::render('Public/Reservation', [
            'settings' => $this->settings(),
            'reservations' => [],
            'searched' => false,
            'error' => null,
        ]);
    }

    /**
     * Cari reservasi berdasarkan:
     * - nomor order
     * - email
     * - nomor WhatsApp
     */
    public function search(Request $request): Response
    {
        $validated = $request->validate([
            'keyword' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $keyword = trim($validated['keyword']);

        $orders = Order::query()
            ->with([
                'items',
                'payments',
            ])
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('order_number', $keyword)
                    ->orWhere('customer_email', $keyword)
                    ->orWhere('customer_phone', $keyword);
            })
            ->latest('id')
            ->get();

        if ($orders->isEmpty()) {
            return Inertia::render('Public/Reservation', [
                'settings' => $this->settings(),
                'reservations' => [],
                'searched' => true,
                'error' => 'Reservasi tidak ditemukan. Silakan periksa nomor order, email, atau nomor WhatsApp Anda.',
            ]);
        }

        $reservations = $orders->map(function (Order $order) {
            $items = $order->items->map(function ($item) {
                return [
                    'productName' => $item->product_name,
                    'visitDate' => $item->visit_date,
                    'quantity' => (int) $item->quantity,
                    'unitPrice' => (float) $item->unit_price,
                    'subtotal' => (float) $item->subtotal,
                ];
            })->values()->toArray();

            return [
                'orderNumber' => $order->order_number,
                'orderToken' => $order->order_token,

                'customerName' => $order->customer_name,

                'status' => $order->status,
                'paymentStatus' => $order->payment_status,

                'subtotal' => (float) $order->subtotal,
                'discountAmount' => (float) $order->discount_amount,
                'totalAmount' => (float) $order->total_amount,

                'createdAt' => $order->created_at?->toISOString(),
                'expiresAt' => $order->expires_at?->toISOString(),

                'items' => $items,
            ];
        })->values()->toArray();

        return Inertia::render('Public/Reservation', [
            'settings' => $this->settings(),
            'reservations' => $reservations,
            'searched' => true,
            'error' => null,
        ]);
    }

    /**
     * Detail satu reservasi.
     */
    public function show(string $orderToken): Response
    {
        $order = Order::query()
            ->with([
                'items',
                'payments',
                'tickets',
            ])
            ->where('order_token', $orderToken)
            ->firstOrFail();

        return Inertia::render('Public/ReservationDetail', [
            'settings' => $this->settings(),

            'reservation' => [
                'orderNumber' => $order->order_number,
                'orderToken' => $order->order_token,

                'customerName' => $order->customer_name,
                'customerEmail' => $order->customer_email,
                'customerPhone' => $order->customer_phone,

                'status' => $order->status,
                'paymentStatus' => $order->payment_status,

                'subtotal' => (float) $order->subtotal,
                'discountAmount' => (float) $order->discount_amount,
                'totalAmount' => (float) $order->total_amount,

                'createdAt' => $order->created_at?->toISOString(),
                'expiresAt' => $order->expires_at?->toISOString(),
                'paidAt' => $order->paid_at?->toISOString(),

                'items' => $order->items->map(function ($item) {
                    return [
                        'productName' => $item->product_name,
                        'visitDate' => $item->visit_date,
                        'quantity' => (int) $item->quantity,
                        'unitPrice' => (float) $item->unit_price,
                        'subtotal' => (float) $item->subtotal,
                    ];
                })->values()->toArray(),

                'tickets' => $order->tickets->map(function ($ticket) {
                    return [
                        'ticketNumber' => $ticket->ticket_number,
                        'productName' => $ticket->product_name,
                        'visitDate' => $ticket->visit_date,
                        'status' => $ticket->status,
                    ];
                })->values()->toArray(),

                'payments' => $order->payments->map(function ($payment) {
                    return [
                        'paymentNumber' => $payment->payment_number,
                        'gateway' => $payment->gateway,
                        'method' => $payment->payment_method,
                        'channel' => $payment->payment_channel,
                        'amount' => (float) $payment->amount,
                        'status' => $payment->status,
                        'expiredAt' => $payment->expired_at?->toISOString(),
                        'paidAt' => $payment->paid_at?->toISOString(),
                    ];
                })->values()->toArray(),
            ],
        ]);
    }

    /**
     * Site settings untuk public layout.
     */
    private function settings(): array
    {
        return SiteSetting::query()
            ->where('is_active', true)
            ->get(['key', 'value'])
            ->mapWithKeys(fn($setting) => [
                $setting->key => $setting->value,
            ])
            ->toArray();
    }
}
