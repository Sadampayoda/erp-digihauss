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
        Schema::create('advance_payment_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('advance_payment_id');
            $table->string('coa')->nullable();
            $table->bigInteger('item_id');
            $table->string('image')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('receipt_invoice_items_quantity')->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('service', 15, 2)->default(0);
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
        Schema::dropIfExists('advance_payment_items');
    }
};
