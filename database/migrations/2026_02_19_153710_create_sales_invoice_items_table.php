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
        Schema::create('sales_invoice_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sales_invoice_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('advance_sale_items_id')->nullable();
            $table->unsignedBigInteger('coa_id')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->string('image')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('service', 15, 2)->default(0);
            $table->decimal('margin', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoice_items');
    }
};
