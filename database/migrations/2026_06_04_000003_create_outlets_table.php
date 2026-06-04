<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained('distributors')->onDelete('restrict');
            $table->foreignId('kota_id')->constrained('cities')->onDelete('restrict');
            $table->string('nama_outlet');
            $table->string('nama_pic');
            $table->string('whatsapp', 25);
            $table->text('alamat_lengkap');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url', 2083)->nullable();
            $table->boolean('featured')->default(false);
            $table->enum('status', ['AKTIF', 'NONAKTIF', 'STOK_KOSONG', 'TUTUP'])->default('AKTIF');
            $table->enum('delivery_mode', ['SELF_DELIVERY', 'RECOMMENDED_SHIPPING_CONTACT', 'PICKUP_ONLY'])->default('SELF_DELIVERY');
            $table->timestamps();

            $table->index(['latitude', 'longitude'], 'idx_outlets_coordinates');
            $table->index(['featured', 'status'], 'idx_outlets_featured_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
