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
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->foreignId('task_id')->constrained()->onDelete('cascade');
    $table->dateTime('scheduled_at')->nullable();
    $table->integer('duration_minutes')->nullable();
    $table->enum('status', ['Pending', 'In Process','Complete'])->default('Pending');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
