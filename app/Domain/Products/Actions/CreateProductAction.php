<?php

namespace App\Domain\Products\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(array $data): Product
    {
        return DB::transaction(function () use ($data) {

            /*
             * Upload image
             */
            if (
                isset($data['image']) &&
                $data['image'] instanceof UploadedFile
            ) {
                $data['image'] = $data['image']->store(
                    'products',
                    'public'
                );
            }

            /*
             * Create product
             */
            $product = Product::create($data);

            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'CREATE',
                module: 'PRODUCT',
                model: $product,
                description: 'Membuat product baru',
                newValues: $product->only([
                    'name',
                    'slug',
                    'description',
                    'image',
                    'is_active',
                    'sort_order',
                ]),
            );

            return $product;
        });
    }
}
