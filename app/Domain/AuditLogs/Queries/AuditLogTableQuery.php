<?php

namespace App\Domain\AuditLogs\Queries;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;

class AuditLogTableQuery
{
    public function builder(): Builder
    {
        return AuditLog::query()
            ->with([
                'user:id,name',
            ])
            ->select([
                'audit_logs.id',
                'audit_logs.user_id',
                'audit_logs.action',
                'audit_logs.module',
                'audit_logs.auditable_type',
                'audit_logs.auditable_id',
                'audit_logs.description',
                'audit_logs.old_values',
                'audit_logs.new_values',
                'audit_logs.ip_address',
                'audit_logs.user_agent',
                'audit_logs.created_at',
            ])
            ->latest('audit_logs.created_at');
    }
}
