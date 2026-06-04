<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kota_id')->constrained('cities')->onDelete('restrict');
            $table->string('nama');
            $table->string('pic');
            $table->string('whatsapp', 25);
            $table->text('alamat');
            $table->boolean('tampil_ke_publik')->default(true);
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
