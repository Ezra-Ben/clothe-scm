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
        // Note: In MySQL, modifying ENUM requires recreating the column
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'accepted', 'rejected', 'ordered', 'received'])->default('pending')->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'ordered', 'received'])->default('pending')->after('quantity');
        });
    }
};
