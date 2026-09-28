<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('pdf_position', 20)->nullable()->after('is_active');
            $table->index(['is_active', 'pdf_position', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex('banners_is_active_pdf_position_sort_order_index');
            $table->dropColumn('pdf_position');
        });
    }
};