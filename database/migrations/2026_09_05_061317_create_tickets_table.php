<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            /*
             * Nomor tiket yang ditampilkan ke customer.
             * Contoh:
             * TKT-20260905-000001
             */
            $table->string('ticket_number', 50)->unique();

            /*
             * Token random untuk QR Code.
             * Jangan gunakan ID sebagai QR payload.
             */
            $table->string('token', 128)->unique();

            /*
             * Snapshot product.
             */
            $table->string('product_name', 150);

            /*
             * Tanggal kunjungan.
             */
            $table->date('visit_date');

            $table->enum('status', [
                'ACTIVE',
                'USED',
                'CANCELLED',
                'EXPIRED',
            ])->default('ACTIVE');

            $table->dateTime('issued_at')->nullable();
            $table->dateTime('used_at')->nullable();
            $table->dateTime('expired_at')->nullable();

            /*
             * User/kasir yang melakukan scan.
             */
            $table->foreignId('used_by')
                ->nullable()
                ->constrained('users')
                ->nullOnUpdate()
                ->nullOnDelete();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('order_item_id');
            $table->index('product_id');
            $table->index('visit_date');
            $table->index('status');
            $table->index('used_by');
            $table->index('issued_at');
            $table->index('used_at');

            $table->index(
                ['visit_date', 'status'],
                'tickets_visit_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
