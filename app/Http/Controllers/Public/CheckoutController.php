<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\PriceResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function show(
        Request $request,
        PriceResolver $priceResolver
    ): Response {
        $validated = $request->validate([
            'product' => [
                'required',
                'string',
                'exists:products,slug',
            ],
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
            'voucher' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $product = Product::query()
            ->where('slug', $validated['product'])
            ->where('is_active', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Tentukan harga berdasarkan tanggal
        |--------------------------------------------------------------------------
        */
        $resolved = $priceResolver->resolve(
            $product,
            $validated['date']
        );

        $unitPrice = $resolved['price'];
        $quantity = (int) $validated['quantity'];

        $subtotal = $unitPrice * $quantity;

        /*
        |--------------------------------------------------------------------------
        | Voucher
        |--------------------------------------------------------------------------
        */
        $discount = null;
        $discountAmount = 0;

        if (!empty($validated['voucher'])) {
            $discount = Discount::query()
                ->where('code', strtoupper(trim($validated['voucher'])))
                ->where('is_active', true)
                ->first();

            if ($discount) {
                $now = now();

                $validDate =
                    (!$discount->start_at || $now->gte($discount->start_at)) &&
                    (!$discount->end_at || $now->lte($discount->end_at));

                $validUsage =
                    $discount->usage_limit === null ||
                    $discount->usage_count < $discount->usage_limit;

                $validMinimum =
                    $subtotal >= (float) $discount->min_purchase;

                if ($validDate && $validUsage && $validMinimum) {
                    if ($discount->type === 'PERCENTAGE') {
                        $discountAmount =
                            $subtotal *
                            ((float) $discount->value / 100);

                        if ($discount->max_discount !== null) {
                            $discountAmount = min(
                                $discountAmount,
                                (float) $discount->max_discount
                            );
                        }
                    } else {
                        $discountAmount = (float) $discount->value;
                    }

                    $discountAmount = min(
                        $discountAmount,
                        $subtotal
                    );
                } else {
                    $discount = null;
                }
            }
        }

        $total = $subtotal - $discountAmount;

        /*
        |--------------------------------------------------------------------------
        | Site Settings
        |--------------------------------------------------------------------------
        */
        $settings = SiteSetting::query()
            ->where('is_active', true)
            ->get(['key', 'value'])
            ->mapWithKeys(function ($setting) {
                return [
                    $setting->key => $this->imageSetting(
                        $setting->key,
                        $setting->value
                    ),
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Kirim data ke React/Inertia
        |--------------------------------------------------------------------------
        */
        return Inertia::render('Public/Checkout', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
            ],

            'date' => $resolved['date'],

            'dayType' => $resolved['day_type'],

            'quantity' => $quantity,

            'unitPrice' => $unitPrice,

            'subtotal' => $subtotal,

            'discount' => $discount
                ? [
                    'code' => $discount->code,
                    'name' => $discount->name,
                    'type' => $discount->type,
                    'value' => (float) $discount->value,
                ]
                : null,

            'discountAmount' => $discountAmount,

            'total' => $total,

            'settings' => $settings,
        ]);
    }

    /**
     * Sementara untuk proses submit checkout.
     *
     * Nanti method ini kita lanjutkan menjadi:
     * Checkout -> Order -> Order Item -> Payment -> Espay QRIS
     */


    public function store(
        Request $request,
        PriceResolver $priceResolver
    ) {
        $validated = $request->validate([
            'product' => [
                'required',
                'string',
                'exists:products,slug',
            ],

            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'voucher' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        /*
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */

        $product = Product::query()
            ->where('slug', $validated['product'])
            ->where('is_active', true)
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | Harga berdasarkan tanggal
    |--------------------------------------------------------------------------
    */

        $resolved = $priceResolver->resolve(
            $product,
            $validated['date']
        );

        $unitPrice = (float) $resolved['price'];

        $quantity = (int) $validated['quantity'];

        $subtotal = $unitPrice * $quantity;

        /*
    |--------------------------------------------------------------------------
    | Voucher
    |--------------------------------------------------------------------------
    */

        $discount = null;
        $discountAmount = 0;

        if (!empty($validated['voucher'])) {

            $discount = Discount::query()
                ->where('code', strtoupper(trim($validated['voucher'])))
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$discount) {
                return back()
                    ->withErrors([
                        'voucher' => 'Voucher tidak valid.',
                    ])
                    ->withInput();
            }

            $now = now();

            // Belum mulai
            if (
                $discount->start_at &&
                $now->lt($discount->start_at)
            ) {
                return back()
                    ->withErrors([
                        'voucher' => 'Voucher belum dapat digunakan.',
                    ])
                    ->withInput();
            }

            // Sudah berakhir
            if (
                $discount->end_at &&
                $now->gt($discount->end_at)
            ) {
                return back()
                    ->withErrors([
                        'voucher' => 'Voucher sudah berakhir.',
                    ])
                    ->withInput();
            }

            // Batas penggunaan
            if (
                $discount->usage_limit !== null &&
                $discount->usage_count >= $discount->usage_limit
            ) {
                return back()
                    ->withErrors([
                        'voucher' => 'Voucher sudah mencapai batas penggunaan.',
                    ])
                    ->withInput();
            }

            // Minimal pembelian
            if (
                $subtotal < (float) $discount->min_purchase
            ) {
                return back()
                    ->withErrors([
                        'voucher' =>
                        'Minimal pembelian untuk voucher ini adalah Rp ' .
                            number_format(
                                (float) $discount->min_purchase,
                                0,
                                ',',
                                '.'
                            ) .
                            '.',
                    ])
                    ->withInput();
            }

            /*
        |--------------------------------------------------------------------------
        | Hitung discount
        |--------------------------------------------------------------------------
        */

            if ($discount->type === 'PERCENTAGE') {

                $discountAmount =
                    $subtotal *
                    ((float) $discount->value / 100);

                if ($discount->max_discount !== null) {
                    $discountAmount = min(
                        $discountAmount,
                        (float) $discount->max_discount
                    );
                }
            } else {

                $discountAmount = (float) $discount->value;
            }

            $discountAmount = min(
                $discountAmount,
                $subtotal
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Total
    |--------------------------------------------------------------------------
    */

        $total = $subtotal - $discountAmount;

        /*
    |--------------------------------------------------------------------------
    | Buat transaksi
    |--------------------------------------------------------------------------
    */

        $order = DB::transaction(function () use (
            $validated,
            $product,
            $resolved,
            $unitPrice,
            $quantity,
            $subtotal,
            $discount,
            $discountAmount,
            $total
        ) {

            /*
        |--------------------------------------------------------------------------
        | Generate nomor order
        |--------------------------------------------------------------------------
        */

            do {
                $orderNumber =
                    'ORD-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(6));
            } while (
                Order::where(
                    'order_number',
                    $orderNumber
                )->exists()
            );

            /*
        |--------------------------------------------------------------------------
        | Token aman untuk public
        |--------------------------------------------------------------------------
        */

            $orderToken = Str::random(64);

            /*
        |--------------------------------------------------------------------------
        | Expired pembayaran
        |--------------------------------------------------------------------------
        |
        | Misalnya customer diberikan waktu 15 menit.
        |
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

                'customer_name' => $validated['name'],

                'customer_email' => $validated['email'],

                'customer_phone' => $validated['phone'],

                'subtotal' => $subtotal,

                'discount_code' => $discount?->code,

                'discount_amount' => $discountAmount,

                'total_amount' => $total,

                'currency' => 'IDR',

                'status' => 'PENDING',

                'payment_status' => 'PENDING',

                'expires_at' => $expiresAt,
            ]);

            /*
        |--------------------------------------------------------------------------
        | Create Order Item
        |--------------------------------------------------------------------------
        */

            OrderItem::create([
                'order_id' => $order->id,

                'product_id' => $product->id,

                'product_name' => $product->name,

                'unit_price' => $unitPrice,

                'quantity' => $quantity,

                'visit_date' => $resolved['date'],

                'subtotal' => $subtotal,
            ]);

            /*
        |--------------------------------------------------------------------------
        | Create Payment
        |--------------------------------------------------------------------------
        */

            Payment::create([
                'order_id' => $order->id,

                'payment_number' =>
                'PAY-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(8)),

                'gateway' => 'ESPAY',

                'payment_method' => 'QRIS',

                'payment_channel' => 'QRIS',

                'amount' => $total,

                'currency' => 'IDR',

                'status' => 'PENDING',

                'expired_at' => $expiresAt,
            ]);

            /*
        |--------------------------------------------------------------------------
        | Update penggunaan voucher
        |--------------------------------------------------------------------------
        */

            if ($discount) {
                $discount->increment('usage_count');
            }

            return $order;
        });

        /*
    |--------------------------------------------------------------------------
    | Redirect ke halaman pembayaran
    |--------------------------------------------------------------------------
    */

        return redirect()->route('public.payment', [
            'order' => $order->order_token,
        ]);
    }
    /**
     * Normalisasi URL image dari SiteSetting.
     */
    private function imageSetting(
        string $key,
        ?string $value
    ): ?string {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        if (str_starts_with($value, '/')) {
            return $value;
        }

        if (str_starts_with($value, 'storage/')) {
            return '/' . $value;
        }

        if (
            str_starts_with($value, 'images/') ||
            str_starts_with($value, 'assets/')
        ) {
            return '/' . $value;
        }

        return '/storage/' . ltrim($value, '/');
    }
}
