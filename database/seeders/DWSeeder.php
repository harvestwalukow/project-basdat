<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penitipan;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Hewan;
use App\Models\PaketLayanan;
use App\Observers\DW\PenitipanObserver;
use App\Observers\DW\PembayaranObserver;
use App\Observers\DW\PenggunaObserver;
use App\Observers\DW\HewanObserver;
use App\Models\DW\DimPaket;

class DWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Dim Paket
        $pakets = PaketLayanan::all();
        foreach ($pakets as $paket) {
            DimPaket::firstOrCreate(
                ['id_paket' => $paket->id_paket],
                [
                    'nama_paket' => $paket->nama_paket,
                    'harga_per_hari' => $paket->harga_per_hari,
                    'is_active' => $paket->is_active
                ]
            );
        }

        // 2. Dim Customer & Staff
        $users = Pengguna::all();
        $penggunaObserver = new PenggunaObserver();
        foreach ($users as $user) {
            $penggunaObserver->created($user);
        }

        // 3. Dim Hewan
        $hewans = Hewan::all();
        $hewanObserver = new HewanObserver();
        foreach ($hewans as $hewan) {
            $hewanObserver->created($hewan);
        }

        // 4. Fact Transaksi (Penitipan)
        // Order by date to ensure proper WaktuKeys
        $penitipans = Penitipan::orderBy('tanggal_masuk')->get();
        $penitipanObserver = new PenitipanObserver();
        foreach ($penitipans as $penitipan) {
            $penitipanObserver->created($penitipan);
        }

        // 5. Fact Keuangan (Pembayaran)
        // Note: PembayaranObserver updates FactKeuangan AND FactTransaksi.
        $pembayarans = Pembayaran::orderBy('created_at')->get();
        $pembayaranObserver = new PembayaranObserver();
        foreach ($pembayarans as $pembayaran) {
            $pembayaranObserver->created($pembayaran);
        }
    }
}
