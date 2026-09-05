<?php

namespace App\Domain\Products\Queries;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductTableQuery
{
    public function builder(): Builder
    {
        return Product::query()
            ->select([
                'id',
                'name',
                'slug',
                'description',
                'image',
                'is_active',
                'sort_order',
                'created_at',
                'updated_at',
            ])
            ->orderBy('sort_order')
            ->orderByDesc('created_at');
    }
}
