<?php

namespace App\Domain\Checkout\DTOs;

use App\Http\Requests\Public\CheckoutRequest;

final readonly class CheckoutData
{
    public function __construct(
        public string $productSlug,
        public string $date,
        public int $quantity,

        public string $name,
        public string $email,
        public string $phone,

        public ?string $voucher,
    ) {}

    public static function fromRequest(
        CheckoutRequest $request
    ): self {
        return new self(
            productSlug: $request->string('product')->toString(),

            date: $request->date('date')->toDateString(),

            quantity: $request->integer('quantity'),

            name: trim(
                $request->string('name')->toString()
            ),

            email: strtolower(
                trim(
                    $request->string('email')->toString()
                )
            ),

            phone: trim(
                $request->string('phone')->toString()
            ),

            voucher: $request->filled('voucher')
                ? strtoupper(
                    trim(
                        $request->string('voucher')->toString()
                    )
                )
                : null,
        );
    }
}