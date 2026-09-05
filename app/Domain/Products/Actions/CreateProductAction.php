<?php

namespace App\Domain\Products\Actions;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
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

            return Product::create($data);
        });
    }
}
