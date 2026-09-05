<?php

namespace App\Domain\ProductPrices\Actions;

use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class DeleteProductPriceAction
{
    public function execute(ProductPrice $productPrice): bool
    {
        return DB::transaction(function () use ($productPrice) {

            return $productPrice->delete();
        });
    }
}
