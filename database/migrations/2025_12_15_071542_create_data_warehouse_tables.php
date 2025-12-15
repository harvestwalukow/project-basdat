<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set database default collation to match existing tables
        DB::statement('ALTER DATABASE `' . env('DB_DATABASE') . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        
        // dim_customer
        Schema::create('dim_customer', function (Blueprint $table) {
            $table->increments('customer_key');
            $table->unsignedBigInteger('id_pengguna')->unique();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100);
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->timestamps();
            
            $table->index('id_pengguna');
        });
        
        // dim_staff
        Schema::create('dim_staff', function (Blueprint $table) {
            $table->increments('staff_key');
            $table->unsignedBigInteger('id_pengguna')->unique();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100);
            $table->string('role', 50);
            $table->string('specialization', 100)->nullable();
            $table->timestamps();
            
            $table->index('id_pengguna');
        });
        
        // dim_hewan
        Schema::create('dim_hewan', function (Blueprint $table) {
            $table->increments('hewan_key');
            $table->unsignedBigInteger('id_hewan')->unique();
            $table->string('nama_hewan', 100);
            $table->string('jenis_hewan', 50);
            $table->string('ras', 100)->nullable();
            $table->integer('umur')->nullable();
            $table->string('jenis_kelamin', 10)->nullable();
            $table->decimal('berat', 5, 2)->nullable();
            $table->timestamps();
            
            $table->index('id_hewan');
        });
        
        // dim_paket
        Schema::create('dim_paket', function (Blueprint $table) {
            $table->increments('paket_key');
            $table->unsignedBigInteger('id_paket')->unique();
            $table->string('nama_paket', 100);
            $table->decimal('harga_per_hari', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('id_paket');
        });
        
        // dim_waktu
        Schema::create('dim_waktu', function (Blueprint $table) {
            $table->increments('waktu_key');
            $table->date('tanggal')->unique();
            $table->integer('tahun');
            $table->integer('bulan');
            $table->string('nama_bulan', 20);
            $table->integer('kuartal');
            $table->integer('hari');
            $table->string('nama_hari', 20);
            $table->integer('minggu_ke');
            $table->boolean('is_weekend')->default(false);
            
            $table->index('tanggal');
            $table->index(['tahun', 'bulan']);
        });
        
        // dim_status_penitipan
        Schema::create('dim_status_penitipan', function (Blueprint $table) {
            $table->increments('status_key');
            $table->string('status', 50)->unique();
            $table->string('deskripsi', 200)->nullable();
        });
        
        // dim_pembayaran
        Schema::create('dim_pembayaran', function (Blueprint $table) {
            $table->increments('pembayaran_key');
            $table->string('metode_pembayaran', 50);
            $table->string('status_pembayaran', 50);
            $table->string('deskripsi', 200)->nullable();
            
            $table->unique(['metode_pembayaran', 'status_pembayaran']); 
        });
        
        // fact_transaksi
        Schema::create('fact_transaksi', function (Blueprint $table) {
            $table->id('transaksi_key');
            $table->unsignedInteger('waktu_key')->nullable();
            $table->unsignedInteger('customer_key')->nullable();
            $table->unsignedInteger('hewan_key')->nullable();
            $table->unsignedInteger('paket_key')->nullable();
            $table->unsignedInteger('staff_key')->nullable();
            $table->unsignedInteger('status_key')->nullable();
            $table->unsignedInteger('pembayaran_key')->nullable();
            
            $table->integer('jumlah_hari')->default(0);
            $table->decimal('total_biaya', 12, 2)->default(0);
            $table->integer('jumlah_transaksi')->default(1);
            
            $table->unsignedBigInteger('id_penitipan')->unique();
            $table->datetime('tanggal_masuk');
            $table->unsignedBigInteger('id_pemilik')->nullable();
            $table->unsignedBigInteger('id_hewan')->nullable();
            $table->unsignedBigInteger('id_paket')->nullable();
            $table->unsignedBigInteger('id_staff')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->string('status_pembayaran', 50)->nullable();
            
            $table->foreign('waktu_key')->references('waktu_key')->on('dim_waktu')->onDelete('set null');
            $table->foreign('customer_key')->references('customer_key')->on('dim_customer')->onDelete('set null');
            $table->foreign('hewan_key')->references('hewan_key')->on('dim_hewan')->onDelete('set null');
            $table->foreign('paket_key')->references('paket_key')->on('dim_paket')->onDelete('set null');
            $table->foreign('staff_key')->references('staff_key')->on('dim_staff')->onDelete('set null');
            $table->foreign('status_key')->references('status_key')->on('dim_status_penitipan')->onDelete('set null');
            $table->foreign('pembayaran_key')->references('pembayaran_key')->on('dim_pembayaran')->onDelete('set null');
            
            $table->index('id_penitipan');
            $table->index('tanggal_masuk');
        });
        
        // fact_kapasitas_harian
        Schema::create('fact_kapasitas_harian', function (Blueprint $table) {
            $table->id('kapasitas_key');
            $table->unsignedInteger('waktu_key')->unique();
            
            $table->integer('total_penitipan')->default(0);
            $table->integer('penitipan_aktif')->default(0);
            $table->integer('penitipan_pending')->default(0);
            $table->integer('penitipan_selesai')->default(0);
            $table->integer('penitipan_dibatalkan')->default(0);
            $table->integer('total_hewan')->default(0);
            
            $table->foreign('waktu_key')->references('waktu_key')->on('dim_waktu')->onDelete('cascade');
        });
        
        // fact_keuangan_periodik
        Schema::create('fact_keuangan_periodik', function (Blueprint $table) {
            $table->id('keuangan_key');
            $table->integer('periode_yyyymm')->unique();
            $table->integer('tahun');
            $table->integer('bulan');
            
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->integer('jumlah_transaksi')->default(0);
            $table->decimal('avg_transaksi', 12, 2)->default(0);
            
            $table->index(['tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_keuangan_periodik');
        Schema::dropIfExists('fact_kapasitas_harian');
        Schema::dropIfExists('fact_transaksi');
        Schema::dropIfExists('dim_pembayaran');
        Schema::dropIfExists('dim_status_penitipan');
        Schema::dropIfExists('dim_waktu');
        Schema::dropIfExists('dim_paket');
        Schema::dropIfExists('dim_hewan');
        Schema::dropIfExists('dim_staff');
        Schema::dropIfExists('dim_customer');
    }
};
