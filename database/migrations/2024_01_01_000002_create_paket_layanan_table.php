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
        Schema::create('paket_layanan', function (Blueprint $table) {
            $table->id('id_paket');
            $table->string('nama_paket');
            $table->text('deskripsi');
            $table->decimal('harga_per_hari', 10, 2);
            $table->text('fasilitas');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_layanan');
    }
};

