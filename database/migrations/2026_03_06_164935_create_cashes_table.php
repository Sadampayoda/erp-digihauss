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
        Schema::create('cashes', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->date('transaction_date');
            $table->enum('type', ['in', 'out']);

            $table->unsignedBigInteger('coa_debit')->nullable();
            $table->unsignedBigInteger('coa_credit')->nullable();
            $table->integer('payment_method')->nullable();

            $table->text('description')->nullable();
            $table->decimal('paid_amount', 20, 2)->default(0);

            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger(column: 'created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashes');
    }
};
