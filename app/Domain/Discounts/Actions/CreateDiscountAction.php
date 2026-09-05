<?php

namespace App\Domain\Discounts\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;

class CreateDiscountAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(array $data): Discount
    {
        return DB::transaction(function () use ($data) {

            /*
             * Create discount
             */
            $discount = Discount::create($data);

            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'CREATE',
                module: 'DISCOUNT',
                model: $discount,
                description: 'Membuat discount baru',
                newValues: $discount->only([
                    'code',
                    'name',
                    'type',
                    'value',
                    'max_discount',
                    'min_purchase',
                    'start_at',
                    'end_at',
                    'usage_limit',
                    'usage_count',
                    'is_active',
                ]),
            );

            return $discount;
        });
    }
}
