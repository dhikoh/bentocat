<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama');
            $table->string('whatsapp', 25)->index();
            $table->text('alamat');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->timestamps();

            $table->index(['latitude', 'longitude'], 'idx_customers_coordinates');
        });

        Schema::create('lead_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer_profiles')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('products')->onDelete('restrict');
            $table->string('varian_level_1');
            $table->string('varian_level_2')->nullable();
            $table->string('varian_level_3')->nullable();
            $table->foreignId('kota_id')->constrained('cities')->onDelete('restrict');
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('restrict');
            $table->foreignId('distributor_id')->constrained('distributors')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('lead_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('lead_requests')->onDelete('cascade');
            $table->enum('action_type', ['VIEW_OUTLET', 'CLICK_WA_OUTLET', 'VIEW_SHIPPING_CONTACT', 'CLICK_WA_SHIPPING_CONTACT'])->index();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_actions');
        Schema::dropIfExists('lead_requests');
        Schema::dropIfExists('customer_profiles');
    }
};
