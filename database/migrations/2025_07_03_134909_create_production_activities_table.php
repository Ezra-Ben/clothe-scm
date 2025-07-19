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
        Schema::create('production_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('type')->nullable(); // e.g., QC Check, Material Addition, Machine Breakdown
            $table->timestamp('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_activities');
    }
};
