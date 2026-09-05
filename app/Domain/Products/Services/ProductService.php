<?php

namespace App\Domain\Products\Services;

use App\Domain\Products\Actions\CreateProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\Actions\DeleteProductAction;
use App\Domain\Products\DTOs\ProductData;
use App\Models\Product;

class ProductService
{
    public function __construct(
        protected CreateProductAction $createProductAction,
        protected UpdateProductAction $updateProductAction,
        protected DeleteProductAction $deleteProductAction,
    ) {}

    public function create(array $data): Product
    {
        return $this->createProductAction->execute(
            ProductData::fromArray($data)->toArray()
        );
    }

    public function update(Product $product, array $data): Product
    {
        return $this->updateProductAction->execute(
            $product,
            ProductData::fromArray($data)->toArray()
        );
    }

    public function delete(Product $product): bool
    {
        return $this->deleteProductAction->execute($product);
    }
}
