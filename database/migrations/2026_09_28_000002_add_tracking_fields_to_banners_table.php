<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('tracking_source', 100)->nullable()->after('pdf_position');
            $table->string('tracking_medium', 100)->nullable()->after('tracking_source');
            $table->string('tracking_campaign', 150)->nullable()->after('tracking_medium');
            $table->string('tracking_content', 150)->nullable()->after('tracking_campaign');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_source',
                'tracking_medium',
                'tracking_campaign',
                'tracking_content',
            ]);
        });
    }
};