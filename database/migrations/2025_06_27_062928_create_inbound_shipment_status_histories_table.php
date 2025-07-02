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
        Schema::create('inbound_shipment_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbound_shipment_id')->constrained()->cascadeOnDelete();
        $table->timestamp('changed_at')->useCurrent();
        $table->foreignId('changed_by')->nullable()->constrained('users'); // optional, track user who changed status
            $table->enum('status', [
          'processing', 
          'in_transit',
          'arrived',    
          'received'   
           ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound_shipment_status_histories');
    }
};
