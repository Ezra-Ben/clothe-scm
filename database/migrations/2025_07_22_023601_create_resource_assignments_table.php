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
        Schema::create('resource_assignments', function (Blueprint $table) {
            $table->id();   
            $table->foreignId('resource_id')->constrained('resources')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('production_batches')->onDelete('cascade');
            $table->string('purpose'); 
            $table->dateTime('assigned_start_time');
            $table->dateTime('assigned_end_time')->nullable();
            $table->integer('expected_duration_minutes')->nullable();
            $table->enum('status', ['planned', 'active', 'completed', 'cancelled'])->default('planned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_assignments');
    }
};
