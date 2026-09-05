<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('day_type', [
                'WEEKDAY',
                'WEEKEND',
                'HOLIDAY',
            ]);

            $table->decimal('price', 15, 2);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['product_id', 'day_type'],
                'product_prices_product_day_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
