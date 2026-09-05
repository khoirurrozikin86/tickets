<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Product
            |--------------------------------------------------------------------------
            */

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            // Snapshot nama produk saat transaksi
            $table->string('product_name', 150);


            /*
            |--------------------------------------------------------------------------
            | Visit
            |--------------------------------------------------------------------------
            */

            // Tanggal kunjungan customer
            $table->date('visit_date');

            // Tipe hari saat harga ditentukan
            $table->enum('day_type', [
                'WEEKDAY',
                'WEEKEND',
                'HOLIDAY',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            // Harga satu tiket saat transaksi
            $table->decimal('unit_price', 15, 2);

            // Jumlah tiket
            $table->unsignedInteger('quantity');

            // unit_price × quantity
            $table->decimal('subtotal', 15, 2);


            /*
            |--------------------------------------------------------------------------
            | Additional Data
            |--------------------------------------------------------------------------
            */

            $table->json('metadata')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('order_id');

            $table->index('product_id');

            $table->index('visit_date');

            $table->index('day_type');

            $table->index(
                ['product_id', 'visit_date'],
                'order_items_product_visit_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
