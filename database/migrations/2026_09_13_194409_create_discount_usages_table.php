<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_usages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('discount_id')
                ->constrained('discounts')
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->decimal('discount_amount', 15, 2);

            $table->timestamp('used_at');

            $table->timestamps();

            $table->unique(
                ['discount_id', 'order_id'],
                'discount_usages_discount_order_unique'
            );

            $table->index('discount_id');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_usages');
    }
};
