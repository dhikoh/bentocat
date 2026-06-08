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
        Schema::table('marketing_logs', function (Blueprint $table) {
            $table->foreignId('outlet_id')->nullable()->after('user_id')->constrained('outlets')->nullOnDelete();
            $table->foreignId('customer_profile_id')->nullable()->after('outlet_id')->constrained('customer_profiles')->nullOnDelete();
            $table->text('agenda')->nullable()->after('activity_details');
            $table->unsignedTinyInteger('rating')->nullable()->after('agenda');
            $table->text('notes')->nullable()->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_logs', function (Blueprint $table) {
            $table->dropForeign(['outlet_id']);
            $table->dropForeign(['customer_profile_id']);
            $table->dropColumn(['outlet_id', 'customer_profile_id', 'agenda', 'rating', 'notes']);
        });
    }
};
