<?php

namespace App\Domain\Banners\Actions;

use App\Domain\Banners\DTOs\BannerData;
use App\Models\Banner;
use App\Domain\AuditLogs\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class CreateBannerAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function execute(BannerData $data): Banner
    {
        return DB::transaction(function () use ($data) {

            $banner = Banner::create($data->toArray());

            $this->auditLogService->log(
                action: 'CREATE',
                module: 'BANNER',
                model: $banner,
                description: 'Membuat banner: ' . ($banner->title ?: 'Tanpa Judul'),
                newValues: $banner->only([
                    'title',
                    'subtitle',
                    'image',
                    'button_text',
                    'button_url',
                    'sort_order',
                    'is_active',
                ]),
            );

            return $banner;
        });
    }
}
