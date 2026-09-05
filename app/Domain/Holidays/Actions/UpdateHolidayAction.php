<?php

namespace App\Domain\Holidays\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

class UpdateHolidayAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Holiday $holiday, array $data): Holiday
    {
        return DB::transaction(function () use ($holiday, $data) {

            /*
             * Simpan data sebelum update
             */
            $oldValues = $holiday->only([
                'date',
                'name',
                'is_active',
            ]);

            /*
             * Update holiday
             */
            $holiday->update($data);

            /*
             * Ambil data terbaru
             */
            $holiday = $holiday->fresh();

            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'UPDATE',
                module: 'HOLIDAY',
                model: $holiday,
                description: 'Mengubah hari libur',
                oldValues: $oldValues,
                newValues: $holiday->only([
                    'date',
                    'name',
                    'is_active',
                ]),
            );

            return $holiday;
        });
    }
}
