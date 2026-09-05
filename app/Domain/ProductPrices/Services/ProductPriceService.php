<?php

namespace App\Domain\ProductPrices\Services;

use App\Domain\ProductPrices\Actions\CreateProductPriceAction;
use App\Domain\ProductPrices\Actions\DeleteProductPriceAction;
use App\Domain\ProductPrices\Actions\UpdateProductPriceAction;
use App\Domain\ProductPrices\DTOs\ProductPriceData;
use App\Models\ProductPrice;

class ProductPriceService
{
    public function __construct(
        protected CreateProductPriceAction $createProductPriceAction,
        protected UpdateProductPriceAction $updateProductPriceAction,
        protected DeleteProductPriceAction $deleteProductPriceAction,
    ) {}

    /**
     * Create Product Price
     */
    public function create(array $data): ProductPrice
    {
        $dto = ProductPriceData::fromArray($data);

        return $this->createProductPriceAction->execute(
            $dto->toArray()
        );
    }

    /**
     * Update Product Price
     */
    public function update(
        ProductPrice $productPrice,
        array $data
    ): ProductPrice {
        $dto = ProductPriceData::fromArray($data);

        return $this->updateProductPriceAction->execute(
            $productPrice,
            $dto->toArray()
        );
    }

    /**
     * Delete Product Price
     */
    public function delete(ProductPrice $productPrice): bool
    {
        return $this->deleteProductPriceAction->execute(
            $productPrice
        );
    }
}
