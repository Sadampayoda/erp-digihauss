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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->string('transaction_number')->unique();
            $table->date('transaction_date');
            $table->unsignedBigInteger('sales_invoice_id');
            $table->unsignedBigInteger('customer');
            $table->unsignedBigInteger('sales')->nullable();

            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('service', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            $table->decimal('paid_amount', 15, 2)->default(0);

            $table->decimal('remaining_amount', 15, 2)->default(0);

            $table->integer('payment_method')->nullable();
            $table->unsignedBigInteger('coa_id')->nullable();

            $table->unsignedTinyInteger('status')->default(0);
            $table->string('description')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_returns');
    }
};
