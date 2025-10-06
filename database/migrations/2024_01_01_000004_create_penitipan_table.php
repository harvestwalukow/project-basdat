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
        Schema::create('penitipan', function (Blueprint $table) {
            $table->id('id_penitipan');
            $table->unsignedBigInteger('id_hewan');
            $table->unsignedBigInteger('id_pemilik');
            $table->unsignedBigInteger('id_staff')->nullable();
            $table->dateTime('tanggal_masuk');
            $table->dateTime('tanggal_keluar');
            $table->enum('status', ['pending', 'aktif', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan_khusus')->nullable();
            $table->decimal('total_biaya', 10, 2);
            $table->timestamps();

            $table->foreign('id_hewan')
                  ->references('id_hewan')
                  ->on('hewan')
                  ->onDelete('cascade');
            
            $table->foreign('id_pemilik')
                  ->references('id_pengguna')
                  ->on('pengguna')
                  ->onDelete('cascade');
            
            $table->foreign('id_staff')
                  ->references('id_pengguna')
                  ->on('pengguna')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penitipan');
    }
};

