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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_penitipan');
            $table->string('nomor_transaksi')->unique();
            $table->decimal('jumlah_bayar', 10, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'e_wallet', 'qris', 'kartu_kredit']);
            $table->enum('status_pembayaran', ['pending', 'lunas', 'gagal'])->default('pending');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();

            $table->foreign('id_penitipan')
                  ->references('id_penitipan')
                  ->on('penitipan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};

