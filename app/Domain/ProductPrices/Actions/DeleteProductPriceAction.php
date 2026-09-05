<?php

namespace App\Domain\ProductPrices\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class DeleteProductPriceAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(ProductPrice $productPrice): bool
    {
        return DB::transaction(function () use ($productPrice) {

            /*
             * Simpan data sebelum dihapus
             */
            $oldValues = $productPrice->only([
                'product_id',
                'day_type',
                'price',
                'is_active',
            ]);


            /*
             * Delete product price
             */
            $deleted = $productPrice->delete();


            /*
             * Audit Log
             */
            if ($deleted) {

                $this->auditLogService->log(
                    action: 'DELETE',
                    module: 'PRODUCT_PRICE',
                    model: $productPrice,
                    description: 'Menghapus product price',
                    oldValues: $oldValues,
                );
            }


            return $deleted;
        });
    }
}
