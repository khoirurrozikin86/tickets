<?php

namespace App\Domain\SiteSettings\Actions;

use App\Models\SiteSetting;
use App\Domain\AuditLogs\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class DeleteSiteSettingAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function execute(SiteSetting $setting): void
    {
        DB::transaction(function () use ($setting) {

            $oldValues = $setting->only([
                'key',
                'label',
                'value',
                'type',
                'group',
                'is_active',
            ]);

            $setting->delete();

            $this->auditLogService->log(
                action: 'DELETE',
                module: 'SITE_SETTING',
                model: $setting,
                description: 'Menghapus site setting',
                oldValues: $oldValues,
            );
        });
    }
}
