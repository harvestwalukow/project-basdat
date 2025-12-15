<?php

namespace App\Observers\DW;

use App\Models\Penitipan;
use App\Models\DW\FactTransaksi;
use App\Models\DW\FactKapasitasHarian;
use App\Models\DW\DimWaktu;
use App\Models\DW\DimCustomer;
use App\Models\DW\DimHewan;
use App\Models\DW\DimPaket;
use App\Models\DW\DimStatusPenitipan;
use Carbon\Carbon;

class PenitipanObserver
{
    /**
     * Handle the Penitipan "created" event.
     */
    public function created(Penitipan $penitipan): void
    {
        $this->syncToFact($penitipan);
    }

    /**
     * Handle the Penitipan "updated" event.
     */
    public function updated(Penitipan $penitipan): void
    {
        $this->syncToFact($penitipan);
    }

    /**
     * Handle the Penitipan "deleted" event.
     */
    public function deleted(Penitipan $penitipan): void
    {
        // Ideally we should soft delete or update status in Fact, 
        // but for now let's just delete the fact record to keep it consistent 
        // or keep it for historical data.
        // Assuming we want to keep historical data in DW, but if the original record is deleted 
        // usually it means it was a mistake. 
        // Let's delete the fact record for now.
        FactTransaksi::where('id_penitipan', $penitipan->id_penitipan)->delete();
    }

    private function syncToFact(Penitipan $penitipan)
    {
        // 1. Get or Create DimWaktu
        $tanggalMasuk = Carbon::parse($penitipan->tanggal_masuk);
        $waktuKey = $this->getWaktuKey($tanggalMasuk);

        // 2. Get Keys for other Dims
        // Customer Key
        $customerKey = null;
        $dimCustomer = DimCustomer::where('id_pengguna', $penitipan->id_pemilik)->first();
        if ($dimCustomer) {
            $customerKey = $dimCustomer->customer_key;
        } else {
            // Create if not exists (should be handled by PenggunaObserver, but for safety)
            // For now, assume null if not found or implementing lazy creation here could be complex.
        }

        // Hewan Key
        $hewanKey = null;
        $dimHewan = DimHewan::where('id_hewan', $penitipan->id_hewan)->first();
        if ($dimHewan) {
            $hewanKey = $dimHewan->hewan_key;
        }

        // Paket Key (from detail_penitipan)
        // A penitipan might have multiple details (packages). FactTransaksi seems to have `id_paket` and `paket_key`.
        // If there are multiple packages, usually FactTransaksi corresponds to one transaction which might be the head.
        // Or FactTransaksi is at grain of "Penitipan". 
        // Looking at `dw_basdat.sql`, `fact_transaksi` has `id_paket`.
        // If there are multiple, maybe it picks the main one.
        // Let's try to find the main package from details.
        $paketKey = null;
        $idPaket = null;
        $mainDetail = $penitipan->detailPenitipan->first(); // Need to ensure relationship is loaded or query it
        if (!$mainDetail) {
             // Try to load it
             $mainDetail = $penitipan->detailPenitipan()->first();
        }
        
        if ($mainDetail) {
            $idPaket = $mainDetail->id_paket;
            $dimPaket = DimPaket::where('id_paket', $idPaket)->first();
            if ($dimPaket) {
                $paketKey = $dimPaket->paket_key;
            }
        }

        // Staff Key
        $staffKey = null;
        if ($penitipan->id_staff) {
            $dimStaff = \App\Models\DW\DimStaff::where('id_pengguna', $penitipan->id_staff)->first();
            if ($dimStaff) {
                $staffKey = $dimStaff->staff_key;
            }
        }

        // Status Key
        $statusKey = null;
        $dimStatus = DimStatusPenitipan::firstOrCreate(['status' => $penitipan->status]);
        $statusKey = $dimStatus->status_key;

        // Pembayaran Key
        // Need to find related payment
        $pembayaranKey = null;
        $pembayaran = $penitipan->pembayaran; // Relationship
        if ($pembayaran) {
            $dimPembayaran = \App\Models\DW\DimPembayaran::firstOrCreate([
                'metode_pembayaran' => $pembayaran->metode_pembayaran,
                'status_pembayaran' => $pembayaran->status_pembayaran
            ]);
            $pembayaranKey = $dimPembayaran->pembayaran_key;
        }

        // Update or Create FactTransaksi
        FactTransaksi::updateOrCreate(
            ['id_penitipan' => $penitipan->id_penitipan],
            [
                'tanggal_masuk' => $penitipan->tanggal_masuk,
                'jumlah_hari' => $mainDetail ? $mainDetail->jumlah_hari : 0, // Approx
                'total_biaya' => $penitipan->total_biaya,
                'id_pemilik' => $penitipan->id_pemilik,
                'id_hewan' => $penitipan->id_hewan,
                'id_paket' => $idPaket,
                'id_staff' => $penitipan->id_staff,
                'status' => $penitipan->status,
                'metode_pembayaran' => $pembayaran ? $pembayaran->metode_pembayaran : null,
                'status_pembayaran' => $pembayaran ? $pembayaran->status_pembayaran : null,
                'waktu_key' => $waktuKey,
                'customer_key' => $customerKey,
                'hewan_key' => $hewanKey,
                'paket_key' => $paketKey,
                'staff_key' => $staffKey,
                'status_key' => $statusKey,
                'pembayaran_key' => $pembayaranKey,
                'jumlah_transaksi' => 1
            ]
        );

        // Also update FactKapasitasHarian?
        // This fact table tracks capacity usage per day.
        // For every day of the stay, we should ideally increment count.
        // But `fact_kapasitas_harian` structure in dump: `waktu_key`, `jumlah_hewan` (agg?), `tanggal_masuk` (why?).
        // Dump values: 2025-10-07, 14. 
        // It looks like an aggregate snapshot.
        // Updating it in real-time from here is hard (concurrency).
        // Maybe better to have a scheduled job or re-calc it.
        // Or simpler: just log the entry if the logic is clear.
        // For now, I will skip complex aggregation for capacities to avoid bugs, 
        // as the user asked for "dashboard... mengambil data dari tabel fact".
        // I will focus on FactTransaksi.
    }

    private function getWaktuKey($date)
    {
        $dateStr = $date->format('Y-m-d');
        $dimWaktu = DimWaktu::where('tanggal', $dateStr)->first();
        
        if (!$dimWaktu) {
            $dimWaktu = DimWaktu::create([
                'tanggal' => $dateStr,
                'hari' => $date->day,
                'bulan' => $date->month,
                'tahun' => $date->year,
                'quarter' => $date->quarter
            ]);
        }
        
        return $dimWaktu->waktu_key;
    }
}
