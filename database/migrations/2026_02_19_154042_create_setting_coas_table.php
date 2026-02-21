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
        Schema::create('setting_coas', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('action');
            $table->unsignedBigInteger('payment_method')->nullable();
            $table->enum('position', ['debit', 'credit']);
            $table->unsignedBigInteger('coa_id');
            $table->boolean('is_active')->default(true);

            $table->string('description')->nullable();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_coas');
    }
};
