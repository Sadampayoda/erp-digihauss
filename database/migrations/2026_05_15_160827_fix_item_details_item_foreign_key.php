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
        Schema::table('item_details', function (Blueprint $table) {
            // drop foreign key lama (ke items)
            $table->dropForeign(['item_id']);

            // buat foreign key baru ke item
            $table->foreign('item_id')
                ->references('id')
                ->on('item')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_details', function (Blueprint $table) {
            // rollback: hapus foreign key ke item
            $table->dropForeign(['item_id']);

            // balikin ke default Laravel (items)
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->cascadeOnDelete();
        });
    }
};
