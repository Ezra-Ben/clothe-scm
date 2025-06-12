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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name',100);
            $table->string('registration_number',50)->unique();
            $table->string('email',100)->unique();
            $table->string('phone',20);
            $table->text('address');
            $table->json('previous_clients')->nullable();
            $table->json('transaction_history')->nullable();
            $table->float('industry_ratings')->nullable();
            $table->json('product_categories');
            $table->json('material_types');
            $table->string('pricing_range',50)->nullable();
            $table->boolean('bulk_availability')->default(false);
            $table->json('certifications');
            $table->string('business_license');
            $table->string('tax_identification');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
