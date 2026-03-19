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
        Schema::create('daily_closing_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('transaction_detail_id');
            $table->foreignId('closing_id')->constrained('daily_closings')->cascadeOnDelete();

            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_detail_id')->constrained()->cascadeOnDelete();


            $table->decimal('sale_price', 15, 2)->default(0);
            $table->decimal('service', 15, 2)->default(0);
            $table->decimal('purchase_price', 15, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_closing_items');
    }
};
