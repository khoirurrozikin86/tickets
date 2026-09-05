<?php

namespace App\Domain\Products\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteProductAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Product $product): bool
    {
        return DB::transaction(function () use ($product) {

            /*
             * Simpan data product sebelum dihapus
             * untuk Audit Log
             */
            $oldValues = $product->only([
                'name',
                'slug',
                'description',
                'image',
                'is_active',
                'sort_order',
            ]);

            /*
             * Simpan path gambar
             * sebelum product dihapus
             */
            $image = $product->image;


            /*
             * Hapus gambar product
             */
            if (
                $image &&
                Storage::disk('public')->exists($image)
            ) {
                Storage::disk('public')->delete($image);
            }


            /*
             * Hapus product
             */
            $deleted = $product->delete();


            /*
             * Audit Log
             */
            if ($deleted) {

                $this->auditLogService->log(
                    action: 'DELETE',
                    module: 'PRODUCT',
                    model: $product,
                    description: 'Menghapus product',
                    oldValues: $oldValues,
                );
            }


            return $deleted;
        });
    }
}
