<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Order Identity
            |--------------------------------------------------------------------------
            */

            $table->string('order_number', 50)
                ->unique();

            $table->string('order_token', 64)
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $table->string('customer_name', 150);

            $table->string('customer_email', 150);

            $table->string('customer_phone', 30);


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 15, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Discount
            |--------------------------------------------------------------------------
            */

            $table->foreignId('discount_id')
                ->nullable()
                ->constrained('discounts')
                ->nullOnUpdate()
                ->nullOnDelete();

            // Snapshot voucher yang digunakan
            $table->string('discount_code', 50)
                ->nullable();

            // Nominal discount saat transaksi
            $table->decimal('discount_amount', 15, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Total
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_amount', 15, 2)
                ->default(0);

            $table->string('currency', 3)
                ->default('IDR');


            /*
            |--------------------------------------------------------------------------
            | Order Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'PENDING',
                'PAID',
                'COMPLETED',
                'CANCELLED',
                'EXPIRED',
                'REFUNDED',
            ])->default('PENDING');


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_status', [
                'UNPAID',
                'PENDING',
                'PAID',
                'FAILED',
                'EXPIRED',
                'REFUNDED',
            ])->default('UNPAID');


            /*
            |--------------------------------------------------------------------------
            | Order Lifecycle
            |--------------------------------------------------------------------------
            */

            // Batas waktu pembayaran
            $table->dateTime('expires_at')
                ->nullable();

            // Waktu pembayaran berhasil
            $table->dateTime('paid_at')
                ->nullable();

            // Waktu order dibatalkan
            $table->dateTime('cancelled_at')
                ->nullable();

            // Waktu order selesai
            $table->dateTime('completed_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Additional Data
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            $table->json('metadata')
                ->nullable();


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

            $table->index('customer_email');

            $table->index('customer_phone');

            $table->index('status');

            $table->index('payment_status');

            $table->index('expires_at');

            $table->index('paid_at');

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
