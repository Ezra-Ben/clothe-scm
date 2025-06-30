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
    Schema::create('procurement_deliveries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('procurement_request_id')->constrained();
    $table->foreignId('supplier_id')->constrained();
    $table->integer('delivered_quantity');
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_deliveries');
    }
};
