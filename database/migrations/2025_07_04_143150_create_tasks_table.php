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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
             $table->string('name');
    $table->text('description')->nullable();
    $table->date('scheduled_date')->nullable();
    $table->integer('average_duration_minutes')->nullable();
    $table->enum('status', ['Unassigned','Assigned','Complete'])->default('Unassigned');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
