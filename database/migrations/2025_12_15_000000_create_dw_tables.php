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
        // 1. Dim Customer
        Schema::create('dim_customer', function (Blueprint $table) {
            $table->bigIncrements('customer_key'); 
            $table->bigInteger('id_pengguna')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->timestamps(); 
        });

        // 2. Dim Hewan
        Schema::create('dim_hewan', function (Blueprint $table) {
            $table->bigIncrements('hewan_key');
            $table->bigInteger('id_hewan')->nullable();
            $table->string('nama_hewan')->nullable();
            $table->string('jenis_hewan')->nullable();
            $table->string('ras')->nullable();
            $table->integer('umur')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->decimal('berat', 8, 2)->nullable();
            $table->timestamps();
        });

        // 3. Dim Paket
        Schema::create('dim_paket', function (Blueprint $table) {
            $table->bigIncrements('paket_key');
            $table->bigInteger('id_paket')->nullable();
            $table->string('nama_paket')->nullable();
            $table->decimal('harga_per_hari', 10, 2)->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->timestamps();
        });

        // 4. Dim Pembayaran
        Schema::create('dim_pembayaran', function (Blueprint $table) {
            $table->bigIncrements('pembayaran_key');
            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->timestamps();
        });

        // 5. Dim Staff
        Schema::create('dim_staff', function (Blueprint $table) {
            $table->bigIncrements('staff_key');
            $table->bigInteger('id_pengguna')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('email')->nullable();
            $table->string('role')->nullable();
            $table->string('specialization')->nullable();
            $table->timestamps();
        });

        // 6. Dim Status Penitipan
        Schema::create('dim_status_penitipan', function (Blueprint $table) {
            $table->bigIncrements('status_key');
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // 7. Dim Waktu
        Schema::create('dim_waktu', function (Blueprint $table) {
            $table->bigIncrements('waktu_key');
            $table->date('tanggal')->nullable();
            $table->integer('hari')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('quarter')->nullable();
            $table->timestamps();
        });

        // 8. Fact Customer
        Schema::create('fact_customer', function (Blueprint $table) {
            $table->id(); // Adding an ID for Laravel convenience, though facts usually have composite PKs.
            $table->bigInteger('id_pemilik')->nullable();
            $table->bigInteger('total_transaksi')->nullable();
            $table->decimal('total_pengeluaran', 32, 2)->nullable();
            $table->unsignedBigInteger('customer_key')->nullable();
            
            // Foreign keys if needed, but for DW often we skip strict FK constraints to allow flexibility, 
            // but for a single DB implementation, strict FKs are fine.
            // I will just index them for now.
            $table->index('customer_key');
            $table->timestamps();
        });

        // 9. Fact Kapasitas Harian
        Schema::create('fact_kapasitas_harian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('waktu_key')->nullable();
            $table->bigInteger('jumlah_hewan')->nullable();
            $table->dateTime('tanggal_masuk')->nullable();
            
            $table->index('waktu_key');
            $table->timestamps();
        });

        // 10. Fact Keuangan
        Schema::create('fact_keuangan', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->double('jumlah_bayar')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->dateTime('tanggal_lookup')->nullable();
            $table->unsignedBigInteger('waktu_key')->nullable();
            $table->unsignedBigInteger('pembayaran_key')->nullable();
            $table->text('jumlah_transaksi')->nullable(); // In SQL dump it was tinytext
            
            $table->index('waktu_key');
            $table->index('pembayaran_key');
            $table->timestamps();
        });

        // 11. Fact Layanan Periodik
        Schema::create('fact_layanan_periodik', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_paket')->nullable();
            $table->bigInteger('jumlah_paket')->nullable();
            $table->decimal('total_pendapatan', 32, 2)->nullable();
            $table->unsignedBigInteger('paket_key')->nullable();
            
            $table->index('paket_key');
            $table->timestamps();
        });

        // 12. Fact Transaksi
        Schema::create('fact_transaksi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_penitipan')->nullable();
            $table->dateTime('tanggal_masuk')->nullable();
            $table->integer('jumlah_hari')->nullable();
            $table->double('total_biaya')->nullable();
            $table->bigInteger('id_pemilik')->nullable();
            $table->bigInteger('id_hewan')->nullable();
            $table->bigInteger('id_paket')->nullable();
            $table->bigInteger('id_staff')->nullable();
            $table->string('status')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran')->nullable();
            
            // Keys
            $table->unsignedBigInteger('waktu_key')->nullable();
            $table->unsignedBigInteger('customer_key')->nullable();
            $table->unsignedBigInteger('hewan_key')->nullable();
            $table->unsignedBigInteger('paket_key')->nullable();
            $table->unsignedBigInteger('staff_key')->nullable();
            $table->unsignedBigInteger('status_key')->nullable();
            $table->unsignedBigInteger('pembayaran_key')->nullable();
            
            $table->integer('jumlah_transaksi')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_transaksi');
        Schema::dropIfExists('fact_layanan_periodik');
        Schema::dropIfExists('fact_keuangan');
        Schema::dropIfExists('fact_kapasitas_harian');
        Schema::dropIfExists('fact_customer');
        Schema::dropIfExists('dim_waktu');
        Schema::dropIfExists('dim_status_penitipan');
        Schema::dropIfExists('dim_staff');
        Schema::dropIfExists('dim_pembayaran');
        Schema::dropIfExists('dim_paket');
        Schema::dropIfExists('dim_hewan');
        Schema::dropIfExists('dim_customer');
    }
};
