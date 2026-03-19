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
        Schema::create('daily_closings', function (Blueprint $table) {
            $table->id();

            $table->date('transaction_date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('total_payment', 15, 2)->default(0);
            $table->decimal('total_hpp', 15, 2)->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->integer('total_quantity')->default(0);

            // CASH
            $table->decimal('cash_expected', 15, 2)->default(0);
            $table->decimal('cash_actual', 15, 2)->nullable();
            $table->decimal('cash_difference', 15, 2)->default(0);

            // STOCK SUMMARY
            $table->integer('total_stock_expected')->default(0);
            $table->integer('total_stock_actual')->nullable();
            $table->integer('total_stock_difference')->default(0);

            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_closings');
    }
};
