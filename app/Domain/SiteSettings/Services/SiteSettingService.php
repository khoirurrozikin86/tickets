<?php

namespace App\Domain\SiteSettings\Services;

use App\Models\SiteSetting;
use App\Domain\AuditLogs\Services\AuditLogService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiteSettingService
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function updateAll(
        array $settings,
        array $files = []
    ): void {
        DB::transaction(function () use ($settings, $files) {

            /*
            |--------------------------------------------------------------------------
            | Update Text / URL / Phone / etc.
            |--------------------------------------------------------------------------
            */

            foreach ($settings as $key => $value) {

                $setting = SiteSetting::query()
                    ->where('key', $key)
                    ->first();

                if (!$setting) {
                    continue;
                }

                $oldValue = $setting->value;

                $setting->update([
                    'value' => $value,
                ]);

                if ($oldValue !== $value) {

                    $this->auditLogService->log(
                        action: 'UPDATE',
                        module: 'SITE_SETTING',
                        model: $setting,
                        description: 'Mengubah site setting: ' . $setting->label,
                        oldValues: [
                            'value' => $oldValue,
                        ],
                        newValues: [
                            'value' => $value,
                        ],
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Image
            |--------------------------------------------------------------------------
            */

            foreach ($files as $key => $file) {

                if (!$file instanceof UploadedFile) {
                    continue;
                }

                $setting = SiteSetting::query()
                    ->where('key', $key)
                    ->first();

                if (!$setting) {
                    continue;
                }

                $oldValue = $setting->value;


                /*
                |--------------------------------------------------------------------------
                | Hapus file lama
                |--------------------------------------------------------------------------
                */

                if ($oldValue) {

                    $oldPath = $this->getStoragePath($oldValue);

                    if (
                        $oldPath &&
                        Storage::disk('public')->exists($oldPath)
                    ) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Simpan file baru
                |--------------------------------------------------------------------------
                */

                $path = $file->store(
                    'settings',
                    'public'
                );

                $newValue = 'storage/' . $path;


                $setting->update([
                    'value' => $newValue,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                $this->auditLogService->log(
                    action: 'UPDATE',
                    module: 'SITE_SETTING',
                    model: $setting,
                    description: 'Mengubah file site setting: ' . $setting->label,
                    oldValues: [
                        'value' => $oldValue,
                    ],
                    newValues: [
                        'value' => $newValue,
                    ],
                );
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Convert database value to storage path
    |--------------------------------------------------------------------------
    */

    private function getStoragePath(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        /*
        | Database:
        | storage/settings/logo.png
        |
        | Storage disk public:
        | settings/logo.png
        */

        if (str_starts_with($value, 'storage/')) {
            return substr($value, strlen('storage/'));
        }

        return $value;
    }
}
