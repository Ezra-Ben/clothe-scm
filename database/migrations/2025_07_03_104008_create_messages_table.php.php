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
         Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
        $table->foreignId('sender_id')->constrained('users');
        $table->text('message');
        $table->timestamps();
         });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
