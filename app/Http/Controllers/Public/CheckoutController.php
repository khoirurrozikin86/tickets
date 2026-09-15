<?php

namespace App\Http\Controllers\Public;

use App\Domain\Checkout\Actions\CreateCheckoutAction;
use App\Domain\Checkout\DTOs\CheckoutData;
use App\Domain\Checkout\Services\CheckoutPricingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\CheckoutRequest;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Domain\Payments\Services\EspayService;
use App\Services\PriceResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function show(
        Request $request,
        CheckoutPricingService $pricingService,
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
            ->where(
                'slug',
                $validated['product']
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        try {
            $pricing =
                $pricingService->calculate(
                    product: $product,
                    date: $validated['date'],
                    quantity: (int) $validated['quantity'],
                    voucher: !empty($validated['voucher'])
                        ? $validated['voucher']
                        : null,
                );
        } catch (\DomainException $e) {
            return back()->withErrors([
                'voucher' => $e->getMessage(),
            ]);
        }

        $settings = $this->settings();

        return Inertia::render(
            'Public/Checkout',
            [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                ],

                'date' =>
                    $pricing['date'],

                'dayType' =>
                    $pricing['day_type'],

                'quantity' =>
                    $pricing['quantity'],

                'unitPrice' =>
                    $pricing['unit_price'],

                'subtotal' =>
                    $pricing['subtotal'],

                'discount' =>
                    $pricing['discount']
                        ? [
                            'code' =>
                                $pricing['discount']->code,

                            'name' =>
                                $pricing['discount']->name,

                            'type' =>
                                $pricing['discount']->type,

                            'value' =>
                                (float)
                                $pricing['discount']->value,
                        ]
                        : null,

                'discountAmount' =>
                    $pricing['discount_amount'],

                'total' =>
                    $pricing['total'],

                'settings' => $settings,
            ]
        );
    }

    /**
     * Membuat Order + Order Item + Payment
     * kemudian membuat QRIS Espay.
     */
    public function store(
        CheckoutRequest $request,
        CheckoutPricingService $pricingService,
        CreateCheckoutAction $createCheckoutAction,
        EspayService $espayService,
    ) {
        $data = CheckoutData::fromRequest(
            $request
        );

        $product = Product::query()
            ->where(
                'slug',
                $data->productSlug
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Validasi & hitung harga sebelum create transaction
        |--------------------------------------------------------------------------
        */

        try {
            $pricing =
                $pricingService->calculate(
                    product: $product,
                    date: $data->date,
                    quantity: $data->quantity,
                    voucher: $data->voucher,
                );
        } catch (\DomainException $e) {
            return back()
                ->withErrors([
                    'voucher' =>
                        $e->getMessage(),
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Create Checkout
        |--------------------------------------------------------------------------
        */

        $result =
            $createCheckoutAction->execute(
                data: $data,
                product: $product,
            );

        $order =
            $result['order'];

        $payment =
            $result['payment'];

        /*
        |--------------------------------------------------------------------------
        | Generate QRIS
        |--------------------------------------------------------------------------
        */

        try {
            $qris =
                $espayService->generateQris(
                    $payment
                );

            $payment->update([
                'gateway_reference' =>
                    $qris['reference_no'],

                'payment_url' =>
                    $qris['qr_url'],

                'qr_code' =>
                    $qris['qr_content'],

                'metadata' => [
                    'external_id' =>
                        $qris['external_id'],

                    'response_code' =>
                        $qris['response_code'],

                    'response_message' =>
                        $qris['response_message'],
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Audit QRIS berhasil dibuat
            |--------------------------------------------------------------------------
            */

            app(\App\Domain\AuditLogs\Services\AuditLogService::class)
                ->log(
                    action: 'UPDATE',
                    module: 'PAYMENT',
                    model: $payment,
                    description:
                        "QRIS berhasil dibuat untuk payment {$payment->payment_number}",
                    oldValues: [
                        'status' =>
                            'PENDING',
                    ],
                    newValues: [
                        'status' =>
                            $payment->status,

                        'gateway_reference' =>
                            $payment->gateway_reference,

                        'payment_method' =>
                            $payment->payment_method,

                        'amount' =>
                            $payment->amount,
                    ],
                );
        } catch (\Throwable $e) {
            Log::error(
                'ESPay QRIS generation failed',
                [
                    'order_number' =>
                        $order->order_number,

                    'payment_number' =>
                        $payment->payment_number,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }

        return redirect()->route(
            'public.payment',
            [
                'order' =>
                    $order->order_token,
            ]
        );
    }

    private function settings(): array
    {
        return SiteSetting::query()
            ->where(
                'is_active',
                true
            )
            ->get([
                'key',
                'value',
            ])
            ->mapWithKeys(
                fn ($setting) => [
                    $setting->key =>
                        $setting->value,
                ]
            )
            ->toArray();
    }
}