<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\DW\FactTransaksi;
use App\Models\DW\FactKeuangan;
use App\Models\DW\DimCustomer;

class DWIntegrationTest extends TestCase
{
    /**
     * Test DW tables are populated.
     */
    public function test_dw_tables_have_data()
    {
        // Assert Fact have data (from Seeder)
        $this->assertGreaterThan(0, FactTransaksi::count(), 'FactTransaksi is empty');
        $this->assertGreaterThan(0, FactKeuangan::count(), 'FactKeuangan is empty');
        $this->assertGreaterThan(0, DimCustomer::count(), 'DimCustomer is empty');
    }

    /**
     * Test Dashboard Data Access.
     */
    public function test_dashboard_access_via_dw_models()
    {
        $fact = FactTransaksi::with(['dimHewan', 'dimCustomer'])->first();
        
        $this->assertNotNull($fact, 'No FactTransaksi record found');
        $this->assertNotNull($fact->dimHewan, 'Relationship dimHewan failed');
        $this->assertNotNull($fact->dimCustomer, 'Relationship dimCustomer failed');
        $this->assertNotNull($fact->pemilik, 'Alias pemilik failed');
        $this->assertNotNull($fact->hewan, 'Alias hewan failed');
        
        // Test Accessor
        // If jumlah_hari is set, tanggal_keluar should be date
        if ($fact->jumlah_hari) {
            $this->assertNotNull($fact->tanggal_keluar, 'Accessor tanggal_keluar failed');
        }
    }
}
