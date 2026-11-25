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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('vimeo_video_id')->nullable();
            $table->string('s3_key')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->bigInteger('size_bytes')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->enum('status', ['pending', 'processing', 'ready', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
