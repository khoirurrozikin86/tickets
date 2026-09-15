<?php

namespace App\Domain\Checkout\Services;

use App\Domain\Checkout\Actions\CreateCheckoutAction;
use App\Domain\Checkout\DTOs\CheckoutData;
use App\Models\Product;

class CheckoutService
{
    public function __construct(
        private readonly CreateCheckoutAction $createCheckoutAction,
    ) {}

    public function execute(
        CheckoutData $data,
        Product $product,
    ): array {
        return $this->createCheckoutAction->execute(
            data: $data,
            product: $product,
        );
    }
}