<?php

namespace App\Domain\ProductPrices\Actions;

use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class CreateProductPriceAction
{
    public function execute(array $data): ProductPrice
    {
        return DB::transaction(function () use ($data) {

            return ProductPrice::create($data);
        });
    }
}
