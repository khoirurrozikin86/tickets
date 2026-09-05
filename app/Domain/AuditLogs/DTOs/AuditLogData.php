<?php

namespace App\Domain\AuditLogs\DTOs;

class AuditLogData
{
    public function __construct(
        public ?int $user_id,
        public string $action,
        public string $module,
        public ?string $auditable_type = null,
        public ?int $auditable_id = null,
        public ?string $description = null,
        public ?array $old_values = null,
        public ?array $new_values = null,
        public ?string $ip_address = null,
        public ?string $user_agent = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'] ?? auth()->id(),

            action: strtoupper(trim($data['action'])),

            module: strtoupper(trim($data['module'])),

            auditable_type: $data['auditable_type'] ?? null,

            auditable_id: isset($data['auditable_id'])
                ? (int) $data['auditable_id']
                : null,

            description: $data['description'] ?? null,

            old_values: $data['old_values'] ?? null,

            new_values: $data['new_values'] ?? null,

            ip_address: $data['ip_address'] ?? request()->ip(),

            user_agent: $data['user_agent'] ?? request()->userAgent(),
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'action' => $this->action,
            'module' => $this->module,
            'auditable_type' => $this->auditable_type,
            'auditable_id' => $this->auditable_id,
            'description' => $this->description,
            'old_values' => $this->old_values,
            'new_values' => $this->new_values,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
        ];
    }
}
