<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            // PostgreSQL specific handling to avoid syntax error near "check"
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement('ALTER TABLE users ALTER COLUMN role TYPE VARCHAR(255)');
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'contributor'");
        } else {
            // MySQL, SQLite, etc.
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 255)->default('contributor')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement('ALTER TABLE users ALTER COLUMN role TYPE VARCHAR(255)');
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'contributor'");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('superadmin', 'editor', 'contributor'))");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['superadmin', 'editor', 'contributor'])->default('contributor')->change();
            });
        }
    }
};
