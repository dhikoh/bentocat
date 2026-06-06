<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('is_mitra');
            $table->index(['is_hidden', 'status'], 'idx_outlets_hidden_status');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('slug');
            $table->index('is_hidden', 'idx_cities_hidden');
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('nama');
            $table->index('is_hidden', 'idx_provinces_hidden');
        });
    }

    public function down(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropIndex('idx_provinces_hidden');
            $table->dropColumn('is_hidden');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex('idx_cities_hidden');
            $table->dropColumn('is_hidden');
        });

        Schema::table('outlets', function (Blueprint $table) {
            $table->dropIndex('idx_outlets_hidden_status');
            $table->dropColumn('is_hidden');
        });
    }
};
