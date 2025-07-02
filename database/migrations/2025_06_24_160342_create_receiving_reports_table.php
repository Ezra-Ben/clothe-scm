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
        Schema::create('receiving_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('inbound_shipments');
    $table->foreignId('received_by')->constrained('users');
    $table->timestamp('received_at');
    $table->enum('condition', ['excellent', 'good', 'damaged']);
    $table->text('discrepancy_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiving_reports');
    }
};
