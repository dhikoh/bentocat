<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('whatsapp', 25);
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('petshop_shipping_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petshop_id')->constrained('outlets')->onDelete('cascade');
            $table->foreignId('shipping_contact_id')->constrained('shipping_contacts')->onDelete('restrict');
            $table->integer('urutan')->default(1);
            $table->timestamps();

            $table->unique(['petshop_id', 'shipping_contact_id'], 'petshop_contact_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petshop_shipping_contacts');
        Schema::dropIfExists('shipping_contacts');
    }
};
