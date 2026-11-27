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
        // 1. Courses
        Schema::table('courses', function (Blueprint $table) {
            $table->string('target_audience', 20)->default('student')->after('id');
            $table->index('target_audience');
        });

        // 2. Subjects - Make exam_id nullable and add target_audience
        // To make foreign key nullable in MySQL, modify column
        DB::statement("ALTER TABLE subjects MODIFY COLUMN exam_id BIGINT UNSIGNED NULL");
        
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('target_audience', 20)->default('student')->after('id');
            $table->index('target_audience');
        });

        // 3. Playlists
        Schema::table('playlists', function (Blueprint $table) {
            $table->string('target_audience', 20)->default('teacher')->after('id');
            $table->index('target_audience');
        });

        // 4. Videos
        Schema::table('videos', function (Blueprint $table) {
            $table->string('target_audience', 20)->default('student')->after('id');
            $table->index('target_audience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['target_audience']);
            $table->dropColumn('target_audience');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(['target_audience']);
            $table->dropColumn('target_audience');
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->dropIndex(['target_audience']);
            $table->dropColumn('target_audience');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropIndex(['target_audience']);
            $table->dropColumn('target_audience');
        });
    }
};
