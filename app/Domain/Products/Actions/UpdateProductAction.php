<?php

namespace App\Domain\Products\Actions;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateProductAction
{
    public function execute(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {

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

            return $product->fresh();
        });
    }
}
