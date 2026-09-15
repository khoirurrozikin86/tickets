<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_records', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Ticket
            |--------------------------------------------------------------------------
            */

            $table->foreignId('ticket_id')
                ->nullable()
                ->constrained('tickets')
                ->nullOnUpdate()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Outlet
            |--------------------------------------------------------------------------
            |
            | Outlet tidak wajib digunakan.
            | NULL diperbolehkan.
            |
            */

            $table->unsignedBigInteger('outlet_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Scan Information
            |--------------------------------------------------------------------------
            */

            $table->enum('result', [
                'SUCCESS',
                'FAILED',
            ]);

            $table->string('reason', 50);

            /*
            |--------------------------------------------------------------------------
            | Scanner
            |--------------------------------------------------------------------------
            */

            $table->foreignId('scanned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnUpdate()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Request Information
            |--------------------------------------------------------------------------
            */

            $table->timestamp('scanned_at');

            $table->ipAddress('ip_address')
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Data
            |--------------------------------------------------------------------------
            */

            $table->json('metadata')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('ticket_id');
            $table->index('outlet_id');
            $table->index('result');
            $table->index('reason');
            $table->index('scanned_by');
            $table->index('scanned_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_records');
    }
};
