<?php

namespace App\Domain\Products\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateProductAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {

            /*
             * Simpan data lama untuk Audit Log
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
             * Upload image baru
             */
            if (
                isset($data['image']) &&
                $data['image'] instanceof UploadedFile
            ) {

                /*
                 * Hapus gambar lama
                 */
                if (
                    $product->image &&
                    Storage::disk('public')->exists($product->image)
                ) {
                    Storage::disk('public')->delete(
                        $product->image
                    );
                }

                /*
                 * Simpan gambar baru
                 */
                $data['image'] = $data['image']->store(
                    'products',
                    'public'
                );
            } else {

                /*
                 * Tidak upload gambar baru,
                 * pertahankan gambar lama.
                 */
                unset($data['image']);
            }


            /*
             * Update product
             */
            $product->update($data);


            /*
             * Refresh data setelah update
             */
            $product->refresh();


            /*
             * Data baru untuk Audit Log
             */
            $newValues = $product->only([
                'name',
                'slug',
                'description',
                'image',
                'is_active',
                'sort_order',
            ]);


            /*
             * Audit Log
             */
            $this->auditLogService->log(
                action: 'UPDATE',
                module: 'PRODUCT',
                model: $product,
                description: 'Mengubah product',
                oldValues: $oldValues,
                newValues: $newValues,
            );


            return $product;
        });
    }
}
