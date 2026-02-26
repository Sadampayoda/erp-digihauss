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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('journal_number');
            $table->date('journal_date');
            $table->string('journal_type')->nullable();
            $table->string('journal_action')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('contact')->nullable();
            $table->text('description')->nullable();
            $table->decimal('total_debit',15,2)->default(0);
            $table->decimal('total_credit',15,2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('journals');
    }
};
