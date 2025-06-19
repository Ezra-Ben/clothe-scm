<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
public function up()
{
    Schema::create('vendors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
	$table->string('business_name');
        $table->string('registration_number')->unique();
        $table->string('contact');
        $table->string('product_category');
        $table->string('business_license_url');
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('vendors');
    }

};
