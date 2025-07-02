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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
           $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('carrier_id')->constrained()->nullable();
    $table->string('tracking_number')->unique();
    $table->enum('status', [
        'pending', 
        'processing', 
        'dispatched', 
        'in_transit', 
        'out_for_delivery', 
        'delivered', 
        'failed'
    ])->default('pending');
    $table->string('service_level'); // 'standard', 'express'
    $table->json('route')->nullable();
    $table->dateTime('estimated_delivery')->nullable();
    $table->dateTime('actual_delivery')->nullable();
    $table->text('notes')->nullable();
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
