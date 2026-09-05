<?php

namespace App\Domain\Holidays\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

class DeleteHolidayAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Holiday $holiday): bool
    {
        return DB::transaction(function () use ($holiday) {

            /*
             * Simpan data sebelum dihapus
             */
            $oldValues = $holiday->only([
                'date',
                'name',
                'is_active',
            ]);

            /*
             * Hapus holiday
             */
            $deleted = $holiday->delete();

            /*
             * Audit Log
             */
            if ($deleted) {
                $this->auditLogService->log(
                    action: 'DELETE',
                    module: 'HOLIDAY',
                    model: $holiday,
                    description: 'Menghapus hari libur',
                    oldValues: $oldValues,
                );
            }

            return $deleted;
        });
    }
}
