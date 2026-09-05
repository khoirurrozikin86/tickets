<?php

namespace App\Domain\ProductPrices\Queries;

use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Builder;

class ProductPriceTableQuery
{
    public function builder(): Builder
    {
        return ProductPrice::query()
            ->with([
                'product:id,name',
            ])
            ->select([
                'product_prices.id',
                'product_prices.product_id',
                'product_prices.day_type',
                'product_prices.price',
                'product_prices.is_active',
                'product_prices.created_at',
                'product_prices.updated_at',
            ]);
    }
}
