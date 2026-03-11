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
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name'); // iPhone 13
            $table->integer('brand')->nullable(); // Apple
            $table->integer('model')->nullable(); // A2633 dll
            $table->integer('stock_on_hand')->default(0);
            $table->integer('stock_available')->default(0);

            $table->string('image')->nullable();
            $table->json('images')->nullable();

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
        Schema::dropIfExists('items');
    }
};
