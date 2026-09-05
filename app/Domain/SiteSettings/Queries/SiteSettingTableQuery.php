<?php

namespace App\Domain\SiteSettings\Queries;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Builder;

class SiteSettingTableQuery
{
    public function builder(): Builder
    {
        return SiteSetting::query()
            ->select([
                'site_settings.id',
                'site_settings.key',
                'site_settings.label',
                'site_settings.value',
                'site_settings.type',
                'site_settings.group',
                'site_settings.description',
                'site_settings.is_active',
                'site_settings.created_at',
                'site_settings.updated_at',
            ])
            ->orderBy('site_settings.group')
            ->orderBy('site_settings.id');
    }
}
