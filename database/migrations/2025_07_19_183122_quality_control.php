<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['passed', 'failed']);
            $table->date('inspection_date');
            $table->integer('defect_count')->default(0);
            $table->text('notes')->nullable();
            $table->text('corrective_action_taken')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_controls');
    }
};
