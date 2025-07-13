<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('tx_ref')->nullable()->unique()->after('payment_method'); 
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('tx_ref');
    });
}

};
