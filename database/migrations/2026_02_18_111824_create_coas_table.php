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

        Schema::create('coas', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->enum('type', [
                'asset',
                'liability',
                'equity',
                'income',
                'expense'
            ]);
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->unsignedTinyInteger('level');

            $table->boolean('is_postable')->default(false);
            $table->boolean('is_active')->default(true);

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coas');
    }
};
