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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('type');

            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('tax_id')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Indonesia');

            $table->integer('payment_terms')->default(0);
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->string('currency')->default('IDR');

            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_account_name')->nullable();

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
