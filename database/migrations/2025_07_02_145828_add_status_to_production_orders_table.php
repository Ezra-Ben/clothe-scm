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
        Schema::table('production_orders', function (Blueprint $table) {
           if (!Schema::hasColumn('production_orders', 'status')) {
                $table->string('status')->default('Pending');
            }
 
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::table('production_orders', function (Blueprint $table) {

        
                if (Schema::hasColumn('production_orders', 'status')) {
                $table->dropColumn('status');
   }
        });
       
    }
};
