<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('outbound_shipments', function (Blueprint $table) {
            $table->string('destination')->nullable()->before('status');
        });

        // Update existing rows: set destination = shipping_address from related customer
        DB::statement('
            UPDATE outbound_shipments os
            JOIN customers c ON os.customer_id = c.id
            SET os.destination = c.shipping_address
        ');
    }

    public function down(): void
    {
        Schema::table('outbound_shipments', function (Blueprint $table) {
            $table->dropColumn('destination');
        });
    }
};
