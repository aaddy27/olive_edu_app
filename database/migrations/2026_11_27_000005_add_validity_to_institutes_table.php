<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make code nullable
        DB::statement("ALTER TABLE institutes MODIFY COLUMN code VARCHAR(50) NULL");

        Schema::table('institutes', function (Blueprint $table) {
            $table->boolean('is_lifetime')->default(false)->after('is_active');
            $table->date('expires_at')->nullable()->after('is_lifetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['is_lifetime', 'expires_at']);
        });

        DB::statement("ALTER TABLE institutes MODIFY COLUMN code VARCHAR(50) NOT NULL");
    }
};
