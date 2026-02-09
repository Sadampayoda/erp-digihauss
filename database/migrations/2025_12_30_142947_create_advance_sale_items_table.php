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
        Schema::create('advance_sale_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('advance_sale_id');
            $table->bigInteger('coa');
            $table->bigInteger('item_id');
            $table->string('image')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('sale_price')->default(0);
            $table->decimal('payment_amount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_sale_items');
    }
};
