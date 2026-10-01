<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_visits', function (Blueprint $table) {
            $table->string('browser', 30)->default('Other')->after('device');
        });
    }

    public function down(): void
    {
        Schema::table('website_visits', function (Blueprint $table) {
            $table->dropColumn('browser');
        });
    }
};