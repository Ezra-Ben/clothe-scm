<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierIdToProcurementRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable()->after('raw_material_id');

            
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
}
