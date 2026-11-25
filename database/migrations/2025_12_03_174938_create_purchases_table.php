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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('purchasable_type');
            $table->unsignedBigInteger('purchasable_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('payment_provider')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('payment_meta')->nullable();
            $table->string('order_id')->unique();
            $table->string('payment_id')->nullable();
            $table->timestamps();
            
            $table->index(['purchasable_type', 'purchasable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
