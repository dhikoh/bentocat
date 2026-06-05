<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('label_level_1')->nullable()->default('Kategori')->after('status');
            $table->string('label_level_2')->nullable()->default('Varian / Aroma')->after('label_level_1');
            $table->string('label_level_3')->nullable()->default('Ukuran / Kemasan')->after('label_level_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['label_level_1', 'label_level_2', 'label_level_3']);
        });
    }
};
