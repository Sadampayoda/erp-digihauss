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
        $tables = [
            'advance_sale_items',
            'sales_invoice_items',
            'sales_return_items',
            'advance_payment_items',
            'receipt_invoice_items',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->bigInteger('item_detail_id')->after('item_id')->nullable();
                $table->string('serial_number')->nullable()->after('item_detail_id');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'advance_sale_items',
            'sales_invoice_items',
            'sales_return_items',
            'advance_payment_items',
            'receipt_invoice_items',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['item_detail_id', 'serial_number']);
            });
        }
    }
};
