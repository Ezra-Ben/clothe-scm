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
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "FedEx Kenya"
            $table->string('contact_phone');
            $table->string('code')->unique(); // e.g., "FEDEXKE"
            $table->json('supported_service_levels'); // ['standard', 'express']
            $table->json('service_areas'); // ['Nairobi', 'Mombasa']
            $table->decimal('base_rate_usd', 10, 2); 
            $table->decimal('max_weight_kg', 10, 2);
            $table->string('tracking_url_template');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
