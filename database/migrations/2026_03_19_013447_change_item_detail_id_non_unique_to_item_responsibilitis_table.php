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
        Schema::table('item_responsibilitis', function (Blueprint $table) {
            $table->dropForeign(['item_detail_id']);

            // 2. Drop unique index
            $table->dropUnique(['item_detail_id']);

            // 3. (optional) jadi index biasa
            $table->index('item_detail_id');

            // 4. Tambahin lagi foreign key
            $table->foreign('item_detail_id')
                ->references('id')
                ->on('item_details')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_responsibilitis', function (Blueprint $table) {
            $table->dropForeign(['item_detail_id']);
            $table->dropIndex(['item_detail_id']);

            $table->unique('item_detail_id');

            $table->foreign('item_detail_id')
                ->references('id')
                ->on('item_details')
                ->cascadeOnDelete();
        });
    }
};
