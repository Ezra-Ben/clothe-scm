<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->string('shipment_type'); 
            $table->string('delivered_by')->nullable();
            $table->string('received_by')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('condition')->nullable();
            $table->text('discrepancies')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for polymorphic relation
            $table->index(['shipment_id', 'shipment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pods');
    }
};