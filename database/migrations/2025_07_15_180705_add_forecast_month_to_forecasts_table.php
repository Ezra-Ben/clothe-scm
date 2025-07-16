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
    Schema::table('forecasts', function (Blueprint $table) {
        $table->date('forecast_month')->after('product_id');
    });
}

public function down()
{
    Schema::table('forecasts', function (Blueprint $table) {
        $table->dropColumn('forecast_month');
    });
}

};
