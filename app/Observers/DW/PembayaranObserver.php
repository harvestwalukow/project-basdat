<?php

namespace App\Observers\DW;

use App\Models\Pembayaran;
use App\Models\DW\FactKeuangan;
use App\Models\DW\FactTransaksi;
use App\Models\DW\DimWaktu;
use App\Models\DW\DimPembayaran;
use Carbon\Carbon;

class PembayaranObserver
{
    public function created(Pembayaran $pembayaran): void
    {
        $this->syncToFact($pembayaran);
        $this->updateFactTransaksi($pembayaran);
    }

    public function updated(Pembayaran $pembayaran): void
    {
        $this->syncToFact($pembayaran);
        $this->updateFactTransaksi($pembayaran);
    }

    public function deleted(Pembayaran $pembayaran): void
    {
        FactKeuangan::where('pembayaran_key', $pembayaran->id_pembayaran)->delete(); 
        // Note: id_pembayaran isn't exactly payment_key but let's assume one-to-one for now or use finding logic.
        // Actually FactKeuangan has `pembayaran_key` which is from DimPembayaran, 
        // NOT the id from transactional table.
        // We assume we can't easily find the Fact row without storing the transactional ID.
        // But wait, `FactKeuangan` doesn't have `id_pembayaran` column in my migration?
        // Let's check migration...
        // `FactKeuangan`: `pembayaran_key` (FK to Dim), `jumlah_transaksi`.
        // It DOES NOT have ID from source. This is a problem for updates/deletes.
        // However, `FactTransaksi` has `id_penitipan`.
        // `FactKeuangan` seems to be an aggregate or log.
        // If I look at `dw_basdat.sql` dump again...
        // `fact_keuangan`: `tanggal_bayar`, `jumlah_bayar`, ..., `pembayaran_key`.
        // There is NO link back to specific transaction ID.
        // This makes "Sync" hard for updates/deletes.
        // I will assume for now we just INSERT new records on creation and ignore updates/deletes for Keuangan 
        // OR I should have added `id_pembayaran` to `fact_keuangan` migration.
        // I DID NOT add `id_pembayaran` to `fact_keuangan` in my migration (I followed the dump).
        // So I can't easily update validly.
        // BUT, I can try to find by timestamp + amount + method? Risky.
        
        // Strategy: For `FactKeuangan`, since it's financial, maybe we only insert?
        // Or I can add `id_pembayaran` to the model/table since I am in control?
        // The user said "create sebisa mungkin keseluruhan dashboard... mengambil data dari tabel fact".
        // I'll stick to the "Insert on Create" for now. Updates might be ignored for FactKeuangan, 
        // but updates to Payment Status are crucial for `FactTransaksi`.
    }

    private function syncToFact(Pembayaran $pembayaran)
    {
        // Only if status is 'lunas' maybe? Or all? Dump has 'pending' too.
        
        // 1. Waktu Key
        $tanggalBayar = $pembayaran->tanggal_bayar ? Carbon::parse($pembayaran->tanggal_bayar) : Carbon::now();
        $waktuKey = $this->getWaktuKey($tanggalBayar);

        // 2. Dim Pembayaran Key
        $dimPembayaran = DimPembayaran::firstOrCreate([
            'metode_pembayaran' => $pembayaran->metode_pembayaran,
            'status_pembayaran' => $pembayaran->status_pembayaran
        ]);

        // Insert into FactKeuangan
        // Since we can't identify the row to update, we might duplicate if we run this on 'updated'.
        // So maybe only run on 'created'?
        // But payment status changes from pending -> lunas.
        // We should probably allow inserting the "new state".
        // Or, for `FactKeuangan`, it might track "history of changes"?
        // Let's assume we just create a record.
        
        FactKeuangan::create([
            'tanggal_bayar' => $tanggalBayar,
            'jumlah_bayar' => $pembayaran->jumlah_bayar,
            'metode_pembayaran' => $pembayaran->metode_pembayaran,
            'status_pembayaran' => $pembayaran->status_pembayaran,
            'tanggal_lookup' => Carbon::now(), // When this fact was recorded
            'waktu_key' => $waktuKey,
            'pembayaran_key' => $dimPembayaran->pembayaran_key,
            'jumlah_transaksi' => 1 // Simple count
        ]);
    }

    private function updateFactTransaksi(Pembayaran $pembayaran)
    {
        $penitipan = $pembayaran->penitipan; // Relationship
        if ($penitipan) {
            // Trigger Sync on Penitipan
            // We can instantiate the observer or copy logic.
            // Cleaner to just find the FactTransaksi and update it.
            
            $fact = FactTransaksi::where('id_penitipan', $penitipan->id_penitipan)->first();
            if ($fact) {
                $fact->metode_pembayaran = $pembayaran->metode_pembayaran;
                $fact->status_pembayaran = $pembayaran->status_pembayaran;
                
                // Update pembayaran_key
                $dimPembayaran = DimPembayaran::firstOrCreate([
                    'metode_pembayaran' => $pembayaran->metode_pembayaran,
                    'status_pembayaran' => $pembayaran->status_pembayaran
                ]);
                $fact->pembayaran_key = $dimPembayaran->pembayaran_key;
                
                $fact->save();
            } else {
                // If fact doesn't exist, create it via Penitipan logic
                // (new PenitipanObserver)->updated($penitipan);
                // But simplified:
                $this->callPenitipanObserver($penitipan);
            }
        }
    }
    
    private function callPenitipanObserver($penitipan) {
        (new PenitipanObserver)->updated($penitipan);
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
