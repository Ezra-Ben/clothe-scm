<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
            
            $table->integer('produced_quantity')->nullable();
            
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->string('status')->default('pending'); // e.g. pending, in_progress, completed, failed
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_batches');
    }
};
