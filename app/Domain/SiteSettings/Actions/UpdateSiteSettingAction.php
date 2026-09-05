<?php

namespace App\Domain\SiteSettings\Actions;

use App\Domain\SiteSettings\DTOs\SiteSettingData;
use App\Models\SiteSetting;
use App\Domain\AuditLogs\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class UpdateSiteSettingAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function execute(
        SiteSetting $setting,
        SiteSettingData $data
    ): SiteSetting {
        return DB::transaction(function () use ($setting, $data) {

            $oldValues = $setting->only([
                'key',
                'label',
                'value',
                'type',
                'group',
                'description',
                'is_active',
            ]);

            $setting->update(
                $data->toArray()
            );

            $this->auditLogService->log(
                action: 'UPDATE',
                module: 'SITE_SETTING',
                model: $setting,
                description: 'Mengubah site setting',
                oldValues: $oldValues,
                newValues: $setting->only([
                    'key',
                    'label',
                    'value',
                    'type',
                    'group',
                    'description',
                    'is_active',
                ]),
            );

            return $setting->refresh();
        });
    }
}
