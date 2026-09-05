<?php

namespace App\Domain\Discounts\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;

class DeleteDiscountAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Discount $discount): bool
    {
        return DB::transaction(function () use ($discount) {

            /*
             * Simpan data sebelum dihapus
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
             * Delete discount
             */
            $deleted = $discount->delete();


            /*
             * Audit Log
             */
            if ($deleted) {

                $this->auditLogService->log(
                    action: 'DELETE',
                    module: 'DISCOUNT',
                    model: $discount,
                    description: 'Menghapus discount',
                    oldValues: $oldValues,
                );
            }


            return $deleted;
        });
    }
}
