<?php

namespace App\Domain\ProductPrices\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class CreateProductPriceAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(array $data): ProductPrice
    {
        return DB::transaction(function () use ($data) {

            /*
             * Create product price
             */
            $productPrice = ProductPrice::create($data);

            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'CREATE',
                module: 'PRODUCT_PRICE',
                model: $productPrice,
                description: 'Membuat product price baru',
                newValues: $productPrice->only([
                    'product_id',
                    'day_type',
                    'price',
                    'is_active',
                ]),
            );

            return $productPrice;
        });
    }
}
