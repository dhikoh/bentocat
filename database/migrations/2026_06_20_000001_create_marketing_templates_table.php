<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('target_audience');
            $table->string('tone');
            $table->text('placeholders')->nullable(); // e.g. "nama_petshop, harga, ketentuan"
            $table->text('base_prompt');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_templates');
    }
};
