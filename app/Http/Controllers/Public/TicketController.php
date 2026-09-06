<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\PriceResolver;
use Carbon\Carbon;
use App\Models\Discount;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function show(
        Product $product,
        Request $request,
        PriceResolver $priceResolver
    ): Response {
        abort_unless($product->is_active, 404);

        $date = $request->input(
            'date',
            now()->toDateString()
        );

        try {
            $resolved = $priceResolver->resolve(
                $product,
                $date
            );
        } catch (\Throwable $e) {
            $resolved = [
                'date' => $date,
                'day_type' => null,
                'price' => null,
                'error' => $e->getMessage(),
            ];
        }

        $settings = SiteSetting::query()
            ->where('is_active', true)
            ->get([
                'key',
                'value',
            ])
            ->mapWithKeys(function ($setting) {
                return [
                    $setting->key => $this->imageSetting(
                        $setting->key,
                        $setting->value
                    ),
                ];
            })
            ->toArray();

        return Inertia::render('Public/TicketCheckout', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'image' => $this->imageUrl(
                    $product->image
                ),
            ],

            'selectedDate' => $resolved['date'],

            'dayType' => $resolved['day_type'],

            'price' => $resolved['price'],

            'priceError' => $resolved['error'] ?? null,

            'minDate' => now()
                ->startOfDay()
                ->toDateString(),

            'settings' => $settings,
        ]);
    }


    /**
     * Mengecek harga berdasarkan tanggal.
     *
     * Digunakan React ketika customer
     * mengganti tanggal.
     */
    public function price(
        Product $product,
        Request $request,
        PriceResolver $priceResolver
    ) {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
        ]);

        try {
            $resolved = $priceResolver->resolve(
                $product,
                $validated['date']
            );

            return response()->json([
                'success' => true,
                'date' => $resolved['date'],
                'day_type' => $resolved['day_type'],
                'price' => $resolved['price'],
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }


    private function imageSetting(
        string $key,
        ?string $value
    ): ?string {
        if (!$value) {
            return $value;
        }

        if (in_array($key, [
            'logo',
            'favicon',
        ], true)) {
            return $this->imageUrl($value);
        }

        return $value;
    }


    private function imageUrl(
        ?string $path
    ): ?string {
        if (!$path) {
            return null;
        }

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://')
        ) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr(
                $path,
                strlen('storage/')
            );
        }

        if (
            str_starts_with($path, 'images/') ||
            str_starts_with($path, 'assets/')
        ) {
            return asset($path);
        }

        return asset(
            'storage/' . $path
        );
    }





    public function voucher(
        Product $product,
        Request $request,
        PriceResolver $priceResolver
    ) {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
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
            'code' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $price = $priceResolver->getPrice(
            $product,
            $validated['date']
        );

        $subtotal = $price * $validated['quantity'];

        $discount = Discount::query()
            ->where('code', strtoupper(trim($validated['code'])))
            ->where('is_active', true)
            ->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak ditemukan.',
            ], 422);
        }

        $now = now();

        if (
            $discount->start_at &&
            $now->lt($discount->start_at)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher belum dapat digunakan.',
            ], 422);
        }

        if (
            $discount->end_at &&
            $now->gt($discount->end_at)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah tidak berlaku.',
            ], 422);
        }

        if (
            $discount->usage_limit !== null &&
            $discount->usage_count >= $discount->usage_limit
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota voucher sudah habis.',
            ], 422);
        }

        if ($subtotal < $discount->min_purchase) {
            return response()->json([
                'success' => false,
                'message' =>
                'Minimal pembelian untuk voucher ini adalah Rp ' .
                    number_format(
                        (float) $discount->min_purchase,
                        0,
                        ',',
                        '.'
                    ) . '.',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | Hitung diskon
    |--------------------------------------------------------------------------
    */

        if ($discount->type === 'PERCENTAGE') {
            $discountAmount =
                $subtotal * ((float) $discount->value / 100);

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

        $total = $subtotal - $discountAmount;

        return response()->json([
            'success' => true,

            'voucher' => [
                'code' => $discount->code,
                'name' => $discount->name,
                'type' => $discount->type,
                'value' => (float) $discount->value,
            ],

            'subtotal' => $subtotal,

            'discount_amount' => $discountAmount,

            'total' => $total,
        ]);
    }
}
