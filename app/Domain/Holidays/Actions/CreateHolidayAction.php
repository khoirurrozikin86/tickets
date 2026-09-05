<?php

namespace App\Domain\Holidays\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

class CreateHolidayAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(array $data): Holiday
    {
        return DB::transaction(function () use ($data) {

            $holiday = Holiday::create($data);

            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'CREATE',
                module: 'HOLIDAY',
                model: $holiday,
                description: 'Membuat hari libur baru',
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
