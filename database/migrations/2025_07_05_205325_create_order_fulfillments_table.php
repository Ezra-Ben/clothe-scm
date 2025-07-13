<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_fulfillments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('estimated_delivery_date')->nullable();
            $table->timestamp('delivered_date')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('updated_by_role')->nullable();
            $table->timestamps();
        });

    }

    
    public function down(): void
    {
        Schema::dropIfExists('order_fulfillments');
    }
};
