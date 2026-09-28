<?php

namespace App\Domain\Banners\Services;

use App\Models\Banner;
use Intervention\Image\ImageManager;

class BannerPdfService
{
    private const PDF_BANNER_WIDTH = 1200;

    private const PDF_BANNER_HEIGHT = 130;

    public function activeBanners(): array
    {
        return Banner::query()
            ->where('is_active', true)
            ->whereIn('pdf_position', ['top', 'bottom'])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('pdf_position')
            ->map(function ($banners) {
                $banner = $banners->first();
                $path = public_path($banner->image);

                if (! is_file($path)) {
                    return null;
                }

                $image = ImageManager::imagick()
                    ->read($path)
                    ->cover(self::PDF_BANNER_WIDTH, self::PDF_BANNER_HEIGHT)
                    ->toJpeg(85);

                return [
                    'src' => $image->toDataUri(),
                    'alt' => $banner->title ?: 'Banner promo',
                ];
            })
            ->filter()
            ->all();
    }
}