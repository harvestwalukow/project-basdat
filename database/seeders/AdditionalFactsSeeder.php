<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DW\FactTransaksi;
use App\Models\DW\FactCustomer;
use App\Models\DW\FactLayananPeriodik;
use Illuminate\Support\Facades\DB;

class AdditionalFactsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Populate FactCustomer (Aggregates from FactTransaksi)
        // Group by customer_key
        $customerStats = FactTransaksi::select(
                'customer_key',
                'id_pemilik',
                DB::raw('count(*) as total_transaksi'),
                DB::raw('sum(total_biaya) as total_pengeluaran')
            )
            ->whereNotNull('customer_key')
            ->groupBy('customer_key', 'id_pemilik')
            ->get();

        foreach ($customerStats as $stat) {
            FactCustomer::updateOrCreate(
                ['customer_key' => $stat->customer_key],
                [
                    'id_pemilik' => $stat->id_pemilik,
                    'total_transaksi' => $stat->total_transaksi,
                    'total_pengeluaran' => $stat->total_pengeluaran ?? 0
                ]
            );
        }

        // 2. Populate FactLayananPeriodik (Aggregates from FactTransaksi)
        // Group by paket_key
        $paketStats = FactTransaksi::select(
                'paket_key',
                'id_paket',
                DB::raw('count(*) as jumlah_paket'),
                DB::raw('sum(total_biaya) as total_pendapatan')
            )
            ->whereNotNull('paket_key')
            ->groupBy('paket_key', 'id_paket')
            ->get();

        foreach ($paketStats as $stat) {
            FactLayananPeriodik::updateOrCreate(
                ['paket_key' => $stat->paket_key],
                [
                    'id_paket' => $stat->id_paket,
                    'jumlah_paket' => $stat->jumlah_paket,
                    'total_pendapatan' => $stat->total_pendapatan ?? 0
                ]
            );
        }
    }
}
