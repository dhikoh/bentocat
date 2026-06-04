<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->boolean('is_mitra')->default(true)->after('featured');
            $table->index(['is_mitra', 'status'], 'idx_outlets_mitra_status');
        });
    }

    public function down(): void
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->dropIndex('idx_outlets_mitra_status');
            $table->dropColumn('is_mitra');
        });
    }
};
