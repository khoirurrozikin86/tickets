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
                'banners.pdf_position',
                'banners.tracking_source',
                'banners.tracking_medium',
                'banners.tracking_campaign',
                'banners.tracking_content',
                'banners.created_at',
            ])
            ->orderBy('banners.sort_order')
            ->orderByDesc('banners.created_at');
    }
}
