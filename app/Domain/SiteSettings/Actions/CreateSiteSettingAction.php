<?php

namespace App\Domain\SiteSettings\Actions;

use App\Domain\SiteSettings\DTOs\SiteSettingData;
use App\Models\SiteSetting;
use App\Domain\AuditLogs\Services\AuditLogService;
use Illuminate\Support\Facades\DB;


class CreateSiteSettingAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function execute(SiteSettingData $data): SiteSetting
    {
        return DB::transaction(function () use ($data) {
            $setting = SiteSetting::create(
                $data->toArray()
            );

            $this->auditLogService->log(
                action: 'CREATE',
                module: 'SITE_SETTING',
                model: $setting,
                description: 'Membuat site setting',
                newValues: $setting->only([
                    'key',
                    'label',
                    'type',
                    'group',
                    'is_active',
                ]),
            );

            return $setting;
        });
    }
}
