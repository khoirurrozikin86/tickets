<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            $table->string('channel', 30);
            // EMAIL / WHATSAPP

            $table->string('type', 50);
            // E_TICKET

            $table->string('recipient', 255);

            $table->string('status', 20)->default('QUEUED');
            // QUEUED / SENT / FAILED

            $table->timestamp('queued_at')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->text('error_message')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('channel');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
