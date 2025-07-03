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
        Schema::table('order_items', function (Blueprint $table) {
            // Nullable because not all order items come from a production order (e.g., from stock)
            $table->foreignId('production_order_id')->nullable()->constrained('production_orders')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['production_order_id']);
            $table->dropColumn('production_order_id');
        });
    }

};
