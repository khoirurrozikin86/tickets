<?php

namespace App\Domain\Discounts\Queries;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Builder;

class DiscountTableQuery
{
    public function builder(): Builder
    {
        return Discount::query()
            ->select([
                'discounts.id',
                'discounts.code',
                'discounts.name',
                'discounts.type',
                'discounts.value',
                'discounts.max_discount',
                'discounts.min_purchase',
                'discounts.start_at',
                'discounts.end_at',
                'discounts.usage_limit',
                'discounts.usage_count',
                'discounts.is_active',
                'discounts.created_at',
                'discounts.updated_at',
            ])
            ->orderBy('discounts.created_at', 'desc');
    }
}
