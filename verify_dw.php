<?php

use App\Models\DW\FactTransaksi;
use App\Models\DW\FactKeuangan;
use App\Models\DW\DimCustomer;

try {
    echo "--- Verifying Data Warehouse Integration ---\n";
    
    // 1. Check Seeding
    $countFact = FactTransaksi::count();
    $countDim = DimCustomer::count();
    echo "FactTransaksi Count: " . $countFact . "\n";
    echo "DimCustomer Count: " . $countDim . "\n";
    
    if ($countFact == 0) {
        throw new Exception("FactTransaksi is empty! Seeding might have failed.");
    }

    // 2. Check Relationships (AdminController dashboard logic)
    echo "Testing Dashboard Query...\n";
    $todaySchedule = FactTransaksi::with(['dimHewan', 'dimCustomer'])->limit(1)->get();
    if ($todaySchedule->count() > 0) {
        $first = $todaySchedule->first();
        echo "Sample Booking: ID " . $first->id_penitipan . "\n";
        echo "- Hewan: " . ($first->dimHewan->nama_hewan ?? 'NULL') . "\n";
        echo "- Pemilik: " . ($first->dimCustomer->nama_lengkap ?? 'NULL') . "\n";
    }

    // 3. Check Aliases (AdminController booking logic)
    echo "Testing Aliases...\n";
    $first = FactTransaksi::first();
    echo "- Owner (Alias): " . ($first->pemilik->nama_lengkap ?? 'NULL') . "\n";
    echo "- Pet (Alias): " . ($first->hewan->nama_hewan ?? 'NULL') . "\n";
    echo "- Tanggal Keluar (Accessor): " . ($first->tanggal_keluar ? $first->tanggal_keluar->format('Y-m-d') : 'NULL') . "\n";

    // 4. Check FactKeuangan (AdminController payments logic)
    $revenue = FactKeuangan::sum('jumlah_bayar');
    echo "Total Revenue (FactKeuangan): " . $revenue . "\n";

    echo "--- Verification Successful ---\n";

} catch (Exception $e) {
    echo "!!! Verification FAILED !!!\n";
    echo $e->getMessage() . "\n";
}
