<?php

namespace App\Domain\Banners\Queries;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Builder;

class BannerTableQuery
{
    public function builder(): Builder
    {
        return Banner::query()
            ->select([
                'banners.id',
                'banners.title',
                'banners.subtitle',
                'banners.image',
                'banners.button_text',
                'banners.button_url',
                'banners.sort_order',
                'banners.is_active',
                'banners.created_at',
            ])
            ->orderBy('banners.sort_order')
            ->orderByDesc('banners.created_at');
    }
}
