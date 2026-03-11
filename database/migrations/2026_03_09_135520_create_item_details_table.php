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
        Schema::create('item_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            // spesifikasi
            $table->string('color')->nullable();
            $table->string('internal_storage')->nullable(); // 128GB
            $table->string('network')->nullable(); // 5G
            $table->string('region')->nullable();

            // identitas device
            $table->string('imei')->unique();
            $table->string('serial_number')->nullable();

            // tipe
            $table->enum('type', ['new', 'second'])->default('new');

            // kelengkapan
            $table->boolean('has_box')->default(false);
            $table->boolean('has_cable')->default(false);
            $table->boolean('has_adapter')->default(false);

            // harga
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->decimal('service', 15, 2)->nullable();

            // supplier
            $table->string('distributor')->nullable();

            // tanggal
            $table->date('purchase_date')->nullable();
            $table->date('sale_date')->nullable();

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
        Schema::dropIfExists('item_details');
    }
};
