<?php

namespace App\Domain\ProductPrices\Actions;

use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class UpdateProductPriceAction
{
    public function execute(
        ProductPrice $productPrice,
        array $data
    ): ProductPrice {
        return DB::transaction(function () use (
            $productPrice,
            $data
        ) {

            $productPrice->update($data);

            return $productPrice->fresh();
        });
    }
}
