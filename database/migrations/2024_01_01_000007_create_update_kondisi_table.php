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
        Schema::create('update_kondisi', function (Blueprint $table) {
            $table->id('id_update');
            $table->unsignedBigInteger('id_penitipan');
            $table->unsignedBigInteger('id_staff');
            $table->text('kondisi_hewan');
            $table->text('aktivitas_hari_ini');
            $table->text('catatan_staff')->nullable();
            $table->string('foto_hewan')->nullable();
            $table->dateTime('waktu_update');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_penitipan')
                  ->references('id_penitipan')
                  ->on('penitipan')
                  ->onDelete('cascade');
            
            $table->foreign('id_staff')
                  ->references('id_pengguna')
                  ->on('pengguna')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_kondisi');
    }
};

