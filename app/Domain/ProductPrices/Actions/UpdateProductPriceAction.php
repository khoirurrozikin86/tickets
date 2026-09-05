<?php

namespace App\Domain\ProductPrices\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class UpdateProductPriceAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(
        ProductPrice $productPrice,
        array $data
    ): ProductPrice {

        return DB::transaction(function () use (
            $productPrice,
            $data
        ) {

            /*
             * Simpan data lama
             */
            $oldValues = $productPrice->only([
                'product_id',
                'day_type',
                'price',
                'is_active',
            ]);


            /*
             * Update product price
             */
            $productPrice->update($data);


            /*
             * Refresh data setelah update
             */
            $productPrice->refresh();


            /*
             * Data baru
             */
            $newValues = $productPrice->only([
                'product_id',
                'day_type',
                'price',
                'is_active',
            ]);


            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'UPDATE',
                module: 'PRODUCT_PRICE',
                model: $productPrice,
                description: 'Mengubah product price',
                oldValues: $oldValues,
                newValues: $newValues,
            );


            return $productPrice;
        });
    }
}
