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
        Schema::table('purchase_return_items', function (Blueprint $table) {
            $table->bigInteger('item_detail_id')->after('item_id')->nullable();
            $table->string('serial_number')->nullable()->after('item_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_return_items', function (Blueprint $table) {
            $table->dropColumn(['item_detail_id', 'serial_number']);
        });
    }
};
