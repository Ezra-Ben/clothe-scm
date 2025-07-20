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
        Schema::create('resources_assignment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->onDelete('cascade');
            $table->foreignId('batch_id')->nullable()->constrained('batches')->onDelete('set null'); // Assign to a batch
            $table->string('assignable_type')->nullable(); // For polymorphic relationships if needed
            $table->unsignedBigInteger('assignable_id')->nullable();
            $table->string('purpose')->nullable(); // e.g., 'machining', 'assembly', 'QC'
            $table->timestamp('assigned_start_time');
            $table->timestamp('assigned_end_time');
            $table->integer('expected_duration_minutes')->nullable();
            $table->string('status')->default('planned'); // 'planned', 'in_progress', 'completed', 'cancelled'
            $table->timestamps();
             $table->index(['assignable_type', 'assignable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources_assignment');
    }
};
