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
        Schema::create('hewan', function (Blueprint $table) {
            $table->id('id_hewan');
            $table->unsignedBigInteger('id_pemilik');
            $table->string('nama_hewan');
            $table->string('jenis_hewan');
            $table->string('ras');
            $table->integer('umur');
            $table->string('jenis_kelamin');
            $table->decimal('berat', 8, 2);
            $table->text('kondisi_khusus')->nullable();
            $table->text('catatan_medis')->nullable();
            $table->timestamps();

            $table->foreign('id_pemilik')
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
        Schema::dropIfExists('hewan');
    }
};

