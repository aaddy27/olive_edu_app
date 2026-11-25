<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create course_subjects pivot table (many-to-many relationship)
        Schema::create('course_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['course_id', 'subject_id']);
        });

        // Modify courses table - remove subject_id since course can have multiple subjects
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
        
        // Add exam_board_id to courses for direct relationship
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('exam_board_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['exam_board_id']);
            $table->dropColumn('exam_board_id');
        });
        
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('subject_id')->after('id')->constrained()->onDelete('cascade');
        });

        Schema::dropIfExists('course_subjects');
    }
};
