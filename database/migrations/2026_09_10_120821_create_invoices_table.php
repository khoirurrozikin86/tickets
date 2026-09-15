<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('invoice_number', 50)
                ->unique();

            $table->date('invoice_date');

            /*
            |--------------------------------------------------------------------------
            | Customer Snapshot
            |--------------------------------------------------------------------------
            */

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_amount', 15, 2)
                ->default(0);

            $table->decimal('total_amount', 15, 2);

            $table->string('currency', 3)
                ->default('IDR');

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string('status', 20)
                ->default('ISSUED');

            $table->timestamp('issued_at');

            $table->text('notes')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('invoice_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};