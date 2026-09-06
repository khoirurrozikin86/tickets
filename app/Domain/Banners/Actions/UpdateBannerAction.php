<?php

namespace App\Domain\Banners\Actions;

use App\Domain\Banners\DTOs\BannerData;
use App\Models\Banner;
use App\Domain\AuditLogs\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class UpdateBannerAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function execute(Banner $banner, BannerData $data): Banner
    {
        return DB::transaction(function () use ($banner, $data) {

            $oldValues = $banner->only([
                'title',
                'subtitle',
                'image',
                'button_text',
                'button_url',
                'sort_order',
                'is_active',
            ]);

            $banner->update($data->toArray());

            $this->auditLogService->log(
                action: 'UPDATE',
                module: 'BANNER',
                model: $banner,
                description: 'Mengubah banner: ' . ($banner->title ?: 'Tanpa Judul'),
                oldValues: $oldValues,
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

            return $banner->refresh();
        });
    }
}
