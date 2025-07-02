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
        Schema::create('inbound_shipments', function (Blueprint $table) {
            $table->id();
             $table->foreignId('supplier_order_id')->constrained();
             $table->foreignId('carrier_id')->constrained();
            $table->string('tracking_number');
            
            
$table->enum('status', [
    'processing', 
    'in_transit',
    'arrived',    
    'received'   
]);
            $table->timestamp('estimated_arrival');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound_shipments');
    }
};
