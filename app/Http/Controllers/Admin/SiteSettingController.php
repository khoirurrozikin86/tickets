<?php

namespace App\Http\Controllers\Admin;

use App\Domain\SiteSettings\Services\SiteSettingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::query()
            ->where('is_active', true)
            ->orderBy('group')
            ->orderBy('id')
            ->get()
            ->groupBy('group');

        return view(
            'super.site-settings.index',
            compact('settings')
        );
    }

    public function update(
        UpdateSiteSettingsRequest $request,
        SiteSettingService $service
    ): RedirectResponse {

        $service->updateAll(
            $request->validated('settings', []),
            $request->file('files', [])
        );

        return back()->with(
            'success',
            'Site settings berhasil diperbarui.'
        );
    }
}
