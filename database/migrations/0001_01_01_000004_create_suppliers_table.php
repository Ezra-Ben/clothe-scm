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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('name')->nullable(); 
            $table->text('address');
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
             $table->string('email')->unique();
             $table->string('phone');
             $table->string('contact_person');
            $table->integer('lead_time_days')->default(7); // Default lead time
            $table->text('contract_terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};