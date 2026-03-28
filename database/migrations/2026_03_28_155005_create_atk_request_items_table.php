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
        Schema::create('atk_request_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('atk_request_id')
                ->comment('Relasi ke ATK request');

            $table->unsignedBigInteger('item_id')->nullable()
                ->comment('Barang utama');

            $table->unsignedBigInteger('item_detail_id')->nullable()
                ->comment('Detail barang (opsional, untuk serial/IMEI)');
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();

            $table->integer('quantity_requested')
                ->comment('Jumlah diajukan');

            $table->integer('quantity_approved')->default(0)
                ->comment('Jumlah disetujui');

            $table->integer('quantity_fulfilled')->default(0)
                ->comment('Jumlah direalisasikan');

            $table->decimal('price', 15, 2)->default(0)
                ->comment('Harga per item (untuk direct expense)');

            $table->string('unit')->nullable();

            $table->unsignedBigInteger('unit_id')->nullable();

            $table->decimal('sub_total', 15, 2)->default(0)
                ->comment('Subtotal');

            $table->text('notes')->nullable()
                ->comment('Catatan item');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atk_request_items');
    }
};
