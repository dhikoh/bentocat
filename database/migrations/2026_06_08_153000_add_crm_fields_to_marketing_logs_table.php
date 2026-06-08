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
            $table->text('followup_feedback')->nullable()->after('activity_details');
            $table->unsignedInteger('potential_closing')->default(0)->after('followup_feedback');
            $table->string('crm_stage', 20)->default('Cold')->after('potential_closing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_logs', function (Blueprint $table) {
            $table->dropColumn(['followup_feedback', 'potential_closing', 'crm_stage']);
        });
    }
};
