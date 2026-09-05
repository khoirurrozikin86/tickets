<?php

namespace App\Domain\AuditLogs\Services;

use App\Domain\AuditLogs\Actions\CreateAuditLogAction;
use App\Domain\AuditLogs\DTOs\AuditLogData;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction,
    ) {}

    public function log(
        string $action,
        string $module,
        ?Model $model = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ) {
        $data = AuditLogData::fromArray([
            'user_id' => auth()->id(),

            'action' => $action,

            'module' => $module,

            'auditable_type' => $model
                ? get_class($model)
                : null,

            'auditable_id' => $model?->getKey(),

            'description' => $description,

            'old_values' => $oldValues,

            'new_values' => $newValues,

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),
        ]);

        return $this->createAuditLogAction->execute(
            $data->toArray()
        );
    }
}
