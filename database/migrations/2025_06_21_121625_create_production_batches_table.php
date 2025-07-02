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
        Schema::create('production_batches', function (Blueprint $table) {
     $table->id();
    $table->string('batch_code')->unique();
    $table->unsignedBigInteger('product_id');
    $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
    $table->timestamp('scheduled_at')->nullable();
    $table->boolean('is_urgent')->default(false);
    $table->string('packaging_status')->default('unassigned');
    $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
    $table->timestamps();

    $table->foreign('product_id')->references('id')->on('products');
    $table->foreign('order_id')->references('id')->on('orders');
});


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
