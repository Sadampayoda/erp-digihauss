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
        Schema::create('item_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_detail_id')->constrained()->cascadeOnDelete();

            $table->integer('battery_health')->nullable();

            $table->string('body_condition')->nullable();
            $table->string('lcd_condition')->nullable();
            $table->string('face_id_condition')->nullable();
            $table->string('battery_condition')->nullable();

            $table->string('front_camera_condition')->nullable();
            $table->string('rear_camera_condition')->nullable();

            $table->string('speaker_top_condition')->nullable();
            $table->string('speaker_bottom_condition')->nullable();

            $table->string('housing_condition')->nullable();
            $table->boolean(column: 'ready')->default(1);

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
        Schema::dropIfExists('item_conditions');
    }
};
