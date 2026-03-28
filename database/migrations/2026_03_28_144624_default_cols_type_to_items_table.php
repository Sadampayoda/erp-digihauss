<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('item')
            ->whereNull('type')
            ->update(['type' => 1]);
        Schema::table('item', function (Blueprint $table) {
            $table->tinyInteger('type')
                ->default(1)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('item', function (Blueprint $table) {
            $table->tinyInteger('type')
                ->nullable()
                ->default(null)
                ->change();
        });
    }
};
