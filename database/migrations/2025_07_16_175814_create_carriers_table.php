<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('active');
            $table->string('contact_phone')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('license_plate')->nullable();
            $table->json('service_areas')->nullable();
            $table->float('max_weight_kg')->nullable();
            $table->float('customer_rating')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};