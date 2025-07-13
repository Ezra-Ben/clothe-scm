<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('procurement_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity_confirmed');
            $table->date('expected_delivery_date')->nullable();
            $table->string('status')->default('pending'); //confirmed, pending, rejected
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('procurement_replies');
    }
};
