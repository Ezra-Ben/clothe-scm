<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('production_batches', function (Blueprint $table) {
        $table->unsignedBigInteger('production_request_id')->nullable()->after('product_id');

        $table->foreign('production_request_id')->references('id')->on('production_requests')->onDelete('set null');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            //
        });
    }
};
