<?php

namespace App\Domain\AuditLogs\Actions;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class CreateAuditLogAction
{
    public function execute(array $data): AuditLog
    {
        return DB::transaction(function () use ($data) {
            return AuditLog::create($data);
        });
    }
}
