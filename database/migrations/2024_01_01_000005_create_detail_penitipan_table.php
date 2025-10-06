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
        Schema::create('detail_penitipan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_penitipan');
            $table->unsignedBigInteger('id_paket');
            $table->integer('jumlah_hari');
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_penitipan')
                  ->references('id_penitipan')
                  ->on('penitipan')
                  ->onDelete('cascade');
            
            $table->foreign('id_paket')
                  ->references('id_paket')
                  ->on('paket_layanan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penitipan');
    }
};

