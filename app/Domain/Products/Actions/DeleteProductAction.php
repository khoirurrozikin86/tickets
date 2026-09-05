<?php

namespace App\Domain\Products\Actions;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteProductAction
{
    public function execute(Product $product): bool
    {
        return DB::transaction(function () use ($product) {

            // Hapus gambar product
            if (
                $product->image &&
                Storage::disk('public')->exists($product->image)
            ) {
                Storage::disk('public')->delete($product->image);
            }

            // Hapus product
            return $product->delete();
        });
    }
}
