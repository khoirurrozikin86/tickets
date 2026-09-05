<?php

namespace App\Domain\Discounts\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;

class UpdateDiscountAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(
        Discount $discount,
        array $data
    ): Discount {

        return DB::transaction(function () use (
            $discount,
            $data
        ) {

            /*
             * Simpan data sebelum update
             */
            $oldValues = $discount->only([
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
            ]);


            /*
             * Update discount
             */
            $discount->update($data);


            /*
             * Ambil data terbaru
             */
            $discount = $discount->fresh();


            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'UPDATE',
                module: 'DISCOUNT',
                model: $discount,
                description: 'Mengubah discount',
                oldValues: $oldValues,
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
