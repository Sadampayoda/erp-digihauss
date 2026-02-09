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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('item_code')->unique();
            $table->string('name');
            $table->string('brand')->index();
            $table->string('series')->index();
            $table->string('model')->index();
            $table->string('variant')->nullable();

            $table->unsignedInteger('storage_gb')->nullable();
            $table->unsignedInteger('ram_gb')->nullable();
            $table->string('color')->nullable();
            $table->unsignedTinyInteger('condition')->default(1);
            $table->string('network')->nullable();
            $table->string('region')->nullable();

            $table->unsignedTinyInteger('status')->default(1);

            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->decimal('service', 15, 2)->default(0);
            $table->decimal('hpp', 15, 2)->default(0);

            $table->integer('stock_on_hand')->default(0);
            $table->integer('stock_available')->default(0);

            $table->string('image')->nullable();
            $table->json('images')->nullable();

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
        Schema::dropIfExists('items');
    }
};
