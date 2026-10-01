<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_visits', function (Blueprint $table) {
            $table->id();
            $table->char('visitor_hash', 64);
            $table->string('path', 255);
            $table->string('referrer_host')->nullable();
            $table->string('device', 20);
            $table->timestamp('visited_at')->useCurrent();
            $table->index(['visited_at', 'visitor_hash']);
            $table->index(['visited_at', 'path']);
            $table->index(['visited_at', 'referrer_host']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_visits');
    }
};
