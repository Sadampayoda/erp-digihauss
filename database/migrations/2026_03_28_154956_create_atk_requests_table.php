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
        Schema::create('atk_requests', function (Blueprint $table) {
            $table->id();

            $table->string('transaction_number')->unique()
                ->comment('Nomor transaksi ATK');

            $table->date('transaction_date')
                ->comment('Tanggal pengajuan');

            $table->unsignedBigInteger('employee_id')->constrained()
                ->comment('Karyawan yang mengajukan');

            $table->date('requested_fulfillment_date')->nullable()
                ->comment('Tanggal kebutuhan');

            $table->text('purpose')->nullable()
                ->comment('Tujuan penggunaan ATK');

            $table->decimal('grand_total', 15, 2)->default(0)
                ->comment('Total nilai ATK');

            $table->decimal('paid_amount', 15, 2)->default(0)
                ->comment('Jumlah yang sudah dibayar');

            $table->unsignedTinyInteger('status')->default(0)
                ->comment('Status transaksi');

            $table->foreignId('approved_by')->nullable()->constrained('users')
                ->comment('User yang menyetujui');

            $table->timestamp('approved_at')->nullable()
                ->comment('Waktu approval');

            $table->integer('payment_method')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atk_requests');
    }
};
