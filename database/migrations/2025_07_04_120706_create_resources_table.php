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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type'); // e.g., 'machine', 'labor', 'workstation'
            $table->text('description')->nullable();
            $table->decimal('capacity_units_per_hour', 8, 2)->nullable(); // e.g., for machines
            $table->string('status')->default('available'); // 'available', 'in_use', 'maintenance', 'offline'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
