<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_material_id');
            $table->unsignedBigInteger('quantity');
            $table->enum('status', ['pending', 'approved', 'ordered', 'received'])->default('pending');

            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('production_order_id')->nullable();

            $table->timestamps();

            $table->foreign('raw_material_id')->references('id')->on('raw_materials')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('production_order_id')->references('id')->on('production_orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('procurement_requests');
    }
};
