<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
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
            | Payment Identity
            |--------------------------------------------------------------------------
            */

            // Nomor payment internal
            // Contoh: PAY-20260905-000001
            $table->string('payment_number', 50)
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Gateway
            |--------------------------------------------------------------------------
            */

            // Contoh: ESPAY
            $table->string('gateway', 50);

            // Contoh: QRIS
            $table->string('payment_method', 50);

            // Channel dari gateway
            $table->string('payment_channel', 50)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Gateway References
            |--------------------------------------------------------------------------
            */

            // Reference dari Espay
            $table->string('gateway_reference', 150)
                ->nullable();

            // Transaction ID dari gateway jika tersedia
            $table->string('gateway_transaction_id', 150)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 15, 2);

            $table->string('currency', 3)
                ->default('IDR');


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'PENDING',
                'PAID',
                'FAILED',
                'EXPIRED',
                'CANCELLED',
                'REFUNDED',
            ])->default('PENDING');


            /*
            |--------------------------------------------------------------------------
            | Payment Lifecycle
            |--------------------------------------------------------------------------
            */

            // Batas waktu pembayaran
            $table->dateTime('expired_at')
                ->nullable();

            // Waktu pembayaran berhasil
            $table->dateTime('paid_at')
                ->nullable();

            // Waktu dibatalkan
            $table->dateTime('cancelled_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | QRIS / Payment Data
            |--------------------------------------------------------------------------
            */

            // URL halaman pembayaran jika gateway memberikan
            $table->text('payment_url')
                ->nullable();

            // QR Code / QRIS payload jika diperlukan
            $table->text('qr_code')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Gateway Callback
            |--------------------------------------------------------------------------
            */

            // Response/callback terakhir dari Espay
            $table->json('callback_payload')
                ->nullable();

            // Data tambahan
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

            $table->index('order_id');

            $table->index('gateway');

            $table->index('payment_method');

            $table->index('status');

            $table->index('gateway_reference');

            $table->index('gateway_transaction_id');

            $table->index('expired_at');

            $table->index('paid_at');

            $table->index('created_at');


            /*
            |--------------------------------------------------------------------------
            | Unique Gateway Reference
            |--------------------------------------------------------------------------
            |
            | Reference gateway tidak boleh digunakan oleh dua payment.
            |
            */

            $table->unique(
                ['gateway', 'gateway_reference'],
                'payments_gateway_reference_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
