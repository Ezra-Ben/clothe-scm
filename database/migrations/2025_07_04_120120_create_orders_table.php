<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending_payment');
            $table->string('payment_method');
            $table->string('tx_ref')->nullable()->unique();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('shipping', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
