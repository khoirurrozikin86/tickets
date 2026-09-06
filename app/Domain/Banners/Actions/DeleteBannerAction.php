<?php

namespace App\Domain\Banners\Actions;

use App\Models\Banner;
use App\Domain\AuditLogs\Services\AuditLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteBannerAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function execute(Banner $banner): void
    {
        DB::transaction(function () use ($banner) {

            $oldValues = $banner->only([
                'title',
                'subtitle',
                'image',
                'button_text',
                'button_url',
                'sort_order',
                'is_active',
            ]);

            if ($banner->image) {
                $path = str_replace('storage/', '', $banner->image);

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $this->auditLogService->log(
                action: 'DELETE',
                module: 'BANNER',
                model: $banner,
                description: 'Menghapus banner: ' . ($banner->title ?: 'Tanpa Judul'),
                oldValues: $oldValues,
            );

            $banner->delete();
        });
    }
}
