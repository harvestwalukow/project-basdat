<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Hewan;
use App\Models\Penitipan;
use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\UpdateKondisi;
use App\Models\DetailPenitipan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// DW Models - Only Fact Tables
use App\Models\DW\FactTransaksi;
use App\Models\DW\FactKeuangan;
use App\Models\DW\FactLayananPeriodik;
use App\Models\DW\FactCustomer;
use App\Models\DW\FactKapasitasHarian;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Get statistics from Fact Tables only
        $totalPenitipanAktif = FactTransaksi::where('status', 'aktif')->count();
        // Count distinct animals that have transactions
        $totalHewan = FactTransaksi::distinct('id_hewan')->count('id_hewan');
        // Count distinct customers that have transactions
        $totalPengguna = FactTransaksi::distinct('id_pemilik')->count('id_pemilik');

        // Get monthly revenue (last 12 months) from FactKeuangan
        $monthlyRevenue = FactKeuangan::where('status_pembayaran', 'lunas')
            ->where('tanggal_bayar', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('YEAR(tanggal_bayar) as year'),
                DB::raw('MONTH(tanggal_bayar) as month'),
                DB::raw('SUM(jumlah_bayar) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Prepare monthly revenue data for chart
        $revenueData = [];
        $revenueLabels = [];
        
        // Indonesian month names (short)
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            // Use Indonesian month names
            $revenueLabels[] = $monthNames[$date->month - 1] . ' ' . $date->format('Y');
            
            $monthRevenue = $monthlyRevenue->first(function($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            $revenueData[] = $monthRevenue ? (float) $monthRevenue->total : 0;
        }

        // Get today's schedule from FactTransaksi and join with operational tables
        $today = Carbon::today();
        $todaySchedule = FactTransaksi::where(function($query) use ($today) {
                $query->whereDate('tanggal_masuk', $today)
                      ->orWhereRaw('DATE_ADD(tanggal_masuk, INTERVAL jumlah_hari DAY) = ?', [$today->format('Y-m-d')]);
            })
            ->where('status', '!=', 'dibatalkan')
            ->get()
            ->map(function($fact) {
                // Attach operational data
                $fact->hewan = Hewan::find($fact->id_hewan);
                $fact->pemilik = Pengguna::find($fact->id_pemilik);
                return $fact;
            });

        // Get latest updates from FactTransaksi (use as activity log)
        $latestUpdates = UpdateKondisi::with(['penitipan.hewan', 'staff'])
            ->orderBy('waktu_update', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenitipanAktif',
            'totalHewan',
            'totalPengguna',
            'revenueData',
            'revenueLabels',
            'todaySchedule',
            'latestUpdates'
        ));
    }

    /**
     * Penitipan/Booking Management
     */
    public function booking()
    {
        // Get all penitipan from FactTransaksi and join with operational tables
        $penitipans = FactTransaksi::orderBy('tanggal_masuk', 'desc')
            ->get()
            ->map(function($fact) {
                // Attach operational data for view compatibility
                $fact->hewan = Hewan::find($fact->id_hewan);
                $fact->pemilik = Pengguna::find($fact->id_pemilik);
                $fact->staff = Pengguna::find($fact->id_staff);
                
                // Use status from fact for payment status
                $fact->status_pembayaran = $fact->status_pembayaran;
                
                return $fact;
            });

        // Calculate statistics
        $totalPenitipan = $penitipans->count();
        $aktifCount = $penitipans->where('status', 'aktif')->count();
        $selesaiCount = $penitipans->where('status', 'selesai')->count();

        // Calculate room capacity using Fact data + operational tables
        $aktivePenitipans = $penitipans->where('status', 'aktif');

        $premiumKucingUsed = 0;
        $basicKucingUsed = 0;
        $premiumAnjingUsed = 0;
        $basicAnjingUsed = 0;
        
        foreach ($aktivePenitipans as $penitipan) {
            // Get data from operational tables
            $hewan = Hewan::find($penitipan->id_hewan);
            $paket = PaketLayanan::find($penitipan->id_paket);
            
            if (!$hewan || !$paket) continue;
            
            $jenisHewan = strtolower($hewan->jenis_hewan ?? '');
            $namaPacket = strtolower($paket->nama_paket ?? '');
            
            // Check packet type
            if (str_contains($namaPacket, 'paket')) {
                $isKucing = str_contains($jenisHewan, 'kucing') || str_contains($jenisHewan, 'cat');
                $isAnjing = str_contains($jenisHewan, 'anjing') || str_contains($jenisHewan, 'dog');
                
                if (str_contains($namaPacket, 'premium')) {
                    if ($isKucing) $premiumKucingUsed++;
                    elseif ($isAnjing) $premiumAnjingUsed++;
                } elseif (str_contains($namaPacket, 'basic')) {
                    if ($isKucing) $basicKucingUsed++;
                    elseif ($isAnjing) $basicAnjingUsed++;
                }
            }
        }

        return view('admin.booking', compact(
            'penitipans',
            'totalPenitipan',
            'aktifCount',
            'selesaiCount',
            'premiumKucingUsed',
            'basicKucingUsed',
            'premiumAnjingUsed',
            'basicAnjingUsed'
        ));
    }

    /**
     * Pets Management
     */
    public function pets()
    {
        // Get unique animals from FactTransaksi (Animals that have transaction history)
        $hewans = FactTransaksi::select('id_hewan', 'id_pemilik')
            ->groupBy('id_hewan', 'id_pemilik')
            ->get()
            ->map(function ($fact) {
                // Get animal data from operational table
                $hewan = Hewan::find($fact->id_hewan);
                
                if ($hewan) {
                    // Attach owner from operational table
                    $hewan->pemilik = Pengguna::find($fact->id_pemilik);
                    
                    // Attach transaction history from Fact
                    $history = FactTransaksi::where('id_hewan', $fact->id_hewan)
                        ->orderBy('tanggal_masuk', 'desc')
                        ->get();
                    $hewan->setRelation('penitipan', $history);
                }
                
                return $hewan;
            })
            ->filter(); // Remove nulls

        // Statistics from FactTransaksi (count unique animals)
        $totalHewan = FactTransaksi::distinct('id_hewan')->count('id_hewan');
        
        // Get all unique animal IDs and check their types from operational table
        $animalIds = FactTransaksi::distinct()->pluck('id_hewan');
        $animals = Hewan::whereIn('id_hewan', $animalIds)->get();
        
        $anjingCount = $animals->filter(function($h) {
            $jenis = strtolower($h->jenis_hewan ?? '');
            return str_contains($jenis, 'anjing') || str_contains($jenis, 'dog');
        })->count();
        
        $kucingCount = $animals->filter(function($h) {
            $jenis = strtolower($h->jenis_hewan ?? '');
            return str_contains($jenis, 'kucing') || str_contains($jenis, 'cat');
        })->count();

        // Daily Capacity from FactKapasitasHarian (last 7 days)
        $dailyCapacity = FactKapasitasHarian::where('tanggal_masuk', '>=', Carbon::now()->subDays(7))
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        $capacityLabels = [];
        $capacityData = [];

        foreach ($dailyCapacity as $capacity) {
            $capacityLabels[] = Carbon::parse($capacity->tanggal_masuk)->format('d M');
            $capacityData[] = $capacity->jumlah_hewan;
        }

        return view('admin.pets', compact(
            'hewans',
            'totalHewan',
            'anjingCount',
            'kucingCount',
            'capacityLabels',
            'capacityData'
        ));
    }

    /**
     * Update Kondisi Management
     */
    public function rooms()
    {
        // Get all update kondisi with relationships
        $updateKondisis = UpdateKondisi::with(['penitipan.hewan', 'penitipan.pemilik', 'staff'])
            ->orderBy('waktu_update', 'desc')
            ->get();

        // Calculate statistics
        $sehatCount = $updateKondisis->where('kondisi_hewan', 'sehat')->count();
        $perluPerhatianCount = $updateKondisis->whereIn('kondisi_hewan', ['sakit', 'perlu perhatian', 'tidak baik'])->count();

        // Get all staff for filter dropdown
        $staffMembers = Pengguna::whereIn('role', ['staff', 'admin'])->orderBy('nama_lengkap')->get();

        // Get active penitipan for adding new update
        $aktivePenitipan = Penitipan::with(['hewan', 'pemilik'])
            ->where('status', 'aktif')
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return view('admin.rooms', compact(
            'updateKondisis',
            'sehatCount',
            'perluPerhatianCount',
            'staffMembers',
            'aktivePenitipan'
        ));
    }

    /**
     * Store New Update Kondisi
     */
    public function storeUpdateKondisi(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'id_penitipan' => 'required|exists:penitipan,id_penitipan',
                'kondisi_hewan' => 'required|string|max:255',
                'aktivitas_hari_ini' => 'required|string',
                'catatan_staff' => 'nullable|string',
                'foto_hewan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Get current staff ID from session
            $staffId = session('user_id');

            // Verify staff ID exists in pengguna table
            $staffExists = Pengguna::find($staffId);
            if (!$staffExists) {
                return back()->with('error', 'Staff tidak valid. Silakan login kembali.');
            }

            // Handle file upload
            $fotoPath = null;
            if ($request->hasFile('foto_hewan')) {
                $file = $request->file('foto_hewan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/update_kondisi'), $filename);
                $fotoPath = 'uploads/update_kondisi/' . $filename;
            }

            // Create new update kondisi
            UpdateKondisi::create([
                'id_penitipan' => $validated['id_penitipan'],
                'id_staff' => $staffId,
                'kondisi_hewan' => $validated['kondisi_hewan'],
                'aktivitas_hari_ini' => $validated['aktivitas_hari_ini'],
                'catatan_staff' => $validated['catatan_staff'],
                'foto_hewan' => $fotoPath,
                'waktu_update' => now(),
            ]);

            return redirect()->route('admin.rooms')->with('success', 'Update kondisi berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get Update Kondisi Details (JSON)
     */
    public function showUpdateKondisi($id)
    {
        try {
            $updateKondisi = UpdateKondisi::with(['penitipan.hewan', 'penitipan.pemilik', 'staff'])
                ->findOrFail($id);
            return response()->json($updateKondisi);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update kondisi tidak ditemukan'], 404);
        }
    }

    /**
     * Update Update Kondisi
     */
    public function updateUpdateKondisi(Request $request, $id)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'id_penitipan' => 'required|exists:penitipan,id_penitipan',
                'kondisi_hewan' => 'required|string|max:255',
                'aktivitas_hari_ini' => 'required|string',
                'catatan_staff' => 'nullable|string',
                'foto_hewan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Find update kondisi
            $updateKondisi = UpdateKondisi::findOrFail($id);

            // Handle file upload
            $fotoPath = $updateKondisi->foto_hewan;
            if ($request->hasFile('foto_hewan')) {
                // Delete old photo if exists
                if ($updateKondisi->foto_hewan && file_exists(public_path($updateKondisi->foto_hewan))) {
                    unlink(public_path($updateKondisi->foto_hewan));
                }

                $file = $request->file('foto_hewan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/update_kondisi'), $filename);
                $fotoPath = 'uploads/update_kondisi/' . $filename;
            }

            // Update update kondisi
            $updateKondisi->update([
                'id_penitipan' => $validated['id_penitipan'],
                'kondisi_hewan' => $validated['kondisi_hewan'],
                'aktivitas_hari_ini' => $validated['aktivitas_hari_ini'],
                'catatan_staff' => $validated['catatan_staff'],
                'foto_hewan' => $fotoPath,
            ]);

            return redirect()->route('admin.rooms')->with('success', 'Update kondisi berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete Update Kondisi
     */
    public function deleteUpdateKondisi($id)
    {
        try {
            // Find update kondisi
            $updateKondisi = UpdateKondisi::findOrFail($id);

            // Delete photo if exists
            if ($updateKondisi->foto_hewan && file_exists(public_path($updateKondisi->foto_hewan))) {
                unlink(public_path($updateKondisi->foto_hewan));
            }

            // Delete update kondisi
            $updateKondisi->delete();

            return redirect()->route('admin.rooms')->with('success', 'Update kondisi berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Paket Layanan Management
     */
    public function service()
    {
        // Get all paket layanan from operational table
        $paketLayanans = PaketLayanan::all();

        // Get usage stats from FactLayananPeriodik
        $stats = FactLayananPeriodik::select('id_paket', DB::raw('SUM(jumlah_paket) as total_usage'))
            ->groupBy('id_paket')
            ->pluck('total_usage', 'id_paket');

        foreach ($paketLayanans as $paket) {
            $paket->detail_penitipan_count = $stats[$paket->id_paket] ?? 0;
        }

        // Calculate statistics
        $totalPaket = PaketLayanan::count();
        $paketAktif = PaketLayanan::where('is_active', true)->count();
        $totalPemesanan = $stats->sum();

        return view('admin.service', compact(
            'paketLayanans',
            'totalPaket',
            'paketAktif',
            'totalPemesanan'
        ));
    }

    /**
     * Payments Management
     */
    public function payments()
    {
        // Use FactTransaksi for payment list
        $pembayarans = FactTransaksi::whereNotNull('status_pembayaran')
            ->orderBy('tanggal_masuk', 'desc')
            ->get()
            ->map(function ($t) {
                // Map properties to match View expectations
                $t->nomor_transaksi = 'TRX-' . str_pad($t->id_penitipan, 5, '0', STR_PAD_LEFT);
                $t->jumlah_bayar = $t->total_biaya;
                
                // Create penitipan object with owner data from operational table
                $penitipanMock = new \stdClass();
                $penitipanMock->pemilik = Pengguna::find($t->id_pemilik);
                $penitipanMock->id_penitipan = $t->id_penitipan;
                $t->setRelation('penitipan', $penitipanMock);
                
                // Get actual id_pembayaran from operational table for update functionality
                $t->id_pembayaran = Pembayaran::where('id_penitipan', $t->id_penitipan)->value('id_pembayaran') ?? 0;
                
                // Set tanggal_bayar from fact (if exists) or get from operational
                if (!isset($t->tanggal_bayar) || !$t->tanggal_bayar) {
                    $pembayaran = Pembayaran::where('id_penitipan', $t->id_penitipan)->first();
                    $t->tanggal_bayar = $pembayaran ? $pembayaran->tanggal_bayar : null;
                }
                
                return $t;
            });

        // Calculate statistics using FactKeuangan
        $totalPendapatan = FactKeuangan::where('status_pembayaran', 'lunas')->sum('jumlah_bayar');
        $totalPembayaran = FactKeuangan::count();

        // Payment method statistics from FactKeuangan
        $paymentMethodStats = FactKeuangan::where('status_pembayaran', 'lunas')
            ->select('metode_pembayaran', DB::raw('count(*) as count'))
            ->groupBy('metode_pembayaran')
            ->get();

        $paymentMethodData = [
            'cash' => 0,
            'transfer' => 0,
            'e_wallet' => 0,
            'qris' => 0,
            'kartu_kredit' => 0,
        ];

        foreach ($paymentMethodStats as $stat) {
            $paymentMethodData[$stat->metode_pembayaran] = $stat->count;
        }

        // Daily revenue for last 7 days from FactKeuangan
        $dailyRevenue = FactKeuangan::where('status_pembayaran', 'lunas')
            ->where('tanggal_bayar', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(tanggal_bayar) as date'),
                DB::raw('SUM(jumlah_bayar) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyRevenueData = [];
        $dailyRevenueLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyRevenueLabels[] = $date->format('d M');
            
            $dayRevenue = $dailyRevenue->firstWhere('date', $date->format('Y-m-d'));
            $dailyRevenueData[] = $dayRevenue ? $dayRevenue->total : 0;
        }

        return view('admin.payments', compact(
            'pembayarans',
            'totalPendapatan',
            'totalPembayaran',
            'paymentMethodData',
            'dailyRevenueData',
            'dailyRevenueLabels'
        ));
    }

    /**
     * Update Pet Data
     */
    public function updatePet(Request $request, $id)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'nama_hewan' => 'required|string|max:100',
                'jenis_hewan' => 'required|in:anjing,kucing',
                'ras' => 'required|string|max:100',
                'umur' => 'required|integer|min:0',
                'berat' => 'required|numeric|min:0',
                'jenis_kelamin' => 'required|in:jantan,betina,tidak diketahui',
                'kondisi_khusus' => 'nullable|string',
                'catatan_medis' => 'nullable|string',
            ]);

            // Find pet
            $hewan = Hewan::findOrFail($id);

            // Update pet data
            $hewan->update($validated);

            return redirect()->route('admin.pets')->with('success', 'Data hewan berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update Payment Status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'metode_pembayaran' => 'required|in:cash,transfer,e_wallet,qris,kartu_kredit',
                'status_pembayaran' => 'required|in:pending,lunas,gagal',
                'tanggal_bayar' => 'nullable|date',
            ]);

            // Find payment
            $pembayaran = Pembayaran::findOrFail($id);

            // Update payment data
            $pembayaran->metode_pembayaran = $validated['metode_pembayaran'];
            $pembayaran->status_pembayaran = $validated['status_pembayaran'];
            
            // Set tanggal_bayar if provided, otherwise use current time if status is lunas
            if ($request->tanggal_bayar) {
                $pembayaran->tanggal_bayar = $validated['tanggal_bayar'];
            } elseif ($validated['status_pembayaran'] === 'lunas' && !$pembayaran->tanggal_bayar) {
                $pembayaran->tanggal_bayar = now();
            }

            $pembayaran->save();

            // Update penitipan status if payment is completed
            if ($validated['status_pembayaran'] === 'lunas') {
                $penitipan = $pembayaran->penitipan;
                if ($penitipan && $penitipan->status === 'pending') {
                    $penitipan->status = 'aktif';
                    $penitipan->save();
                }
            }

            return redirect()->route('admin.payments')->with('success', 'Status pembayaran berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store New Paket Layanan
     */
    public function storePaket(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'nama_paket' => 'required|string|max:100',
                'deskripsi' => 'required|string',
                'harga_per_hari' => 'required|numeric|min:0',
                'fasilitas' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            // Create new paket layanan
            PaketLayanan::create([
                'nama_paket' => $validated['nama_paket'],
                'deskripsi' => $validated['deskripsi'],
                'harga_per_hari' => $validated['harga_per_hari'],
                'fasilitas' => $validated['fasilitas'] ?? null,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return redirect()->route('admin.service')->with('success', 'Paket layanan berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show Paket Layanan (JSON)
     */
    public function showPaket($id)
    {
        try {
            $paket = PaketLayanan::withCount('detailPenitipan')->findOrFail($id);
            return response()->json($paket);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Paket tidak ditemukan'], 404);
        }
    }

    /**
     * Update Paket Layanan
     */
    public function updatePaket(Request $request, $id)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'nama_paket' => 'required|string|max:100',
                'deskripsi' => 'required|string',
                'harga_per_hari' => 'required|numeric|min:0',
                'fasilitas' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            // Find paket
            $paket = PaketLayanan::findOrFail($id);

            // Update paket data
            $paket->update([
                'nama_paket' => $validated['nama_paket'],
                'deskripsi' => $validated['deskripsi'],
                'harga_per_hari' => $validated['harga_per_hari'],
                'fasilitas' => $validated['fasilitas'] ?? null,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return redirect()->route('admin.service')->with('success', 'Paket layanan berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle Paket Status (AJAX)
     */
    public function togglePaketStatus(Request $request, $id)
    {
        try {
            $paket = PaketLayanan::findOrFail($id);
            $paket->is_active = $request->input('is_active', false);
            $paket->save();

            return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete Paket Layanan
     */
    public function deletePaket($id)
    {
        try {
            $paket = PaketLayanan::findOrFail($id);
            
            // Check if paket has related bookings
            if ($paket->detailPenitipan()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus paket yang sudah memiliki pemesanan!');
            }

            $paket->delete();

            return redirect()->route('admin.service')->with('success', 'Paket layanan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Staff Management
     */
    public function staff()
    {
        // Get all staff from operational table
        $staffMembers = Pengguna::whereIn('role', ['staff', 'admin'])->get();
        
        $employees = $staffMembers->map(function($emp) {
            // Get task count from FactTransaksi
            $taskCount = FactTransaksi::where('id_staff', $emp->id_pengguna)->count();
            
            // Map specialization
            $specialization = $emp->specialization ?? 'roomer';
            
            $department = ucfirst($specialization);
            $position = 'Pet ' . ucfirst($specialization);
            
            $specializationName = $specialization; 
            switch ($specialization) {
                case 'groomer': $specializationName = 'Grooming & Perawatan'; break;
                case 'handler': $specializationName = 'Penanganan Hewan & Pick-up/Delivery'; break;
                case 'trainer': $specializationName = 'Pelatihan Hewan'; break;
                default: $specializationName = 'General Staff'; break;
            }
            
            // Bonus based on task count
            $bonus = $taskCount * 50000;
            
            return [
                'id' => $emp->id_pengguna,
                'name' => $emp->nama_lengkap,
                'position' => $position,
                'status' => 'active',
                'department' => $department,
                'specialization_code' => $specialization,
                'email' => $emp->email,
                'phone' => $emp->no_telepon ?? '-',
                'shift' => 'Pagi (08:00 - 16:00)',
                'specialization' => $specializationName,
                'experience' => '12 bulan',
                'joinDate' => $emp->created_at ? $emp->created_at->format('d M Y') : '-',
                'rating' => 4.5,
                'salary' => 5000000,
                'bonus' => $bonus,
                'task_count' => $taskCount
            ];
        })->toArray();

        // Department Stats
        $groomerCount = count(array_filter($employees, fn($e) => $e['specialization_code'] === 'groomer'));
        $handlerCount = count(array_filter($employees, fn($e) => $e['specialization_code'] === 'handler'));
        $trainerCount = count(array_filter($employees, fn($e) => $e['specialization_code'] === 'trainer'));
        
        $departmentStats = [
            ['name' => 'Groomer', 'employees' => $groomerCount],
            ['name' => 'Handler', 'employees' => $handlerCount],
            ['name' => 'Trainer', 'employees' => $trainerCount],
        ];

        return view('admin.staff', compact('employees', 'departmentStats'));
    }

    /**
     * Store New Staff
     */
    public function storeStaff(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|unique:pengguna,email',
                'password' => 'required|string|min:6',
                'no_telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'role' => 'required|in:staff',
                'specialization' => 'required|in:groomer,handler,trainer',
            ]);

            Pengguna::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'no_telepon' => $validated['no_telepon'],
                'alamat' => $validated['alamat'],
                'role' => 'staff',
                'specialization' => $validated['specialization'],
            ]);

            return redirect()->route('admin.staff')->with('success', 'Karyawan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update Staff
     */
    public function updateStaff(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|unique:pengguna,email,' . $id . ',id_pengguna',
                'no_telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'role' => 'required|in:staff',
                'specialization' => 'required|in:groomer,handler,trainer',
                'password' => 'nullable|string|min:6',
            ]);

            $staff = Pengguna::findOrFail($id);
            
            $updateData = [
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'no_telepon' => $validated['no_telepon'],
                'alamat' => $validated['alamat'],
                'role' => 'staff',
                'specialization' => $validated['specialization'],
            ];

            // Update password only if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = bcrypt($validated['password']);
            }

            $staff->update($updateData);

            return redirect()->route('admin.staff')->with('success', 'Data karyawan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete Staff
     */
    public function deleteStaff($id)
    {
        try {
            $staff = Pengguna::findOrFail($id);
            
            // Check if staff has any related records
            if ($staff->staffPenitipans()->count() > 0 || $staff->updateKondisis()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus karyawan yang memiliki data terkait!');
            }

            $staff->delete();

            return redirect()->route('admin.staff')->with('success', 'Karyawan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get Staff Details (JSON)
     */
    public function showStaff($id)
    {
        try {
            $staff = Pengguna::findOrFail($id);
            return response()->json($staff);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Staff tidak ditemukan'], 404);
        }
    }

    /**
     * Reports & Analytics
     */
    public function reports(Request $request)
    {
        $timeRange = $request->input('timeRange', 'month');
        
        // Determine date range based on timeRange
        switch ($timeRange) {
            case '3months':
                $startDate = Carbon::now()->subMonths(3)->startOfMonth();
                $periodCount = 3;
                $periodType = 'month';
                break;
            case '6months':
                $startDate = Carbon::now()->subMonths(6)->startOfMonth();
                $periodCount = 6;
                $periodType = 'month';
                break;
            case 'year':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth(); // Last 12 months
                $periodCount = 12;
                $periodType = 'month';
                break;
            case 'month':
            default:
                $startDate = Carbon::now()->subDays(29); // Last 30 days
                $periodCount = 30;
                $periodType = 'day';
                break;
        }
        
        $endDate = Carbon::now();

        // Total Revenue from FactKeuangan
        $totalRevenue = FactKeuangan::whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        // Total Bookings from FactTransaksi
        $totalBookings = FactTransaksi::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->count();

        // Active Customers from FactTransaksi (using id_pemilik instead of customer_key)
        $activeCustomers = FactTransaksi::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->distinct('id_pemilik')
            ->count('id_pemilik');

        // Top Customers from FactCustomer
        $topCustomers = FactCustomer::orderBy('total_pengeluaran', 'desc')
            ->limit(10)
            ->get()
            ->map(function($factCustomer) {
                $customer = Pengguna::find($factCustomer->id_pemilik);
                return [
                    'nama' => $customer ? $customer->nama_lengkap : 'Unknown',
                    'email' => $customer ? $customer->email : '-',
                    'total_transaksi' => $factCustomer->total_transaksi,
                    'total_pengeluaran' => $factCustomer->total_pengeluaran
                ];
            });

        // Revenue Chart Data from FactKeuangan
        $revenueChartData = [
            'labels' => [],
            'data' => []
        ];

        if ($periodType === 'day') {
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $revenue = FactKeuangan::whereDate('tanggal_bayar', $date->format('Y-m-d'))
                    ->where('status_pembayaran', 'lunas')
                    ->sum('jumlah_bayar');
                
                $revenueChartData['labels'][] = $date->format('d M');
                $revenueChartData['data'][] = round($revenue / 1000000, 2);
            }
        } else {
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $revenue = FactKeuangan::whereMonth('tanggal_bayar', $month->month)
                    ->whereYear('tanggal_bayar', $month->year)
                    ->where('status_pembayaran', 'lunas')
                    ->sum('jumlah_bayar');
                
                $revenueChartData['labels'][] = $month->format('M Y');
                $revenueChartData['data'][] = round($revenue / 1000000, 2);
            }
        }

        // Booking Chart Data from FactTransaksi
        $bookingChartData = [
            'labels' => [],
            'bookings' => [],
            'customers' => []
        ];

        if ($periodType === 'day') {
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $bookings = FactTransaksi::whereDate('tanggal_masuk', $date->format('Y-m-d'))
                    ->count();
                
                $customers = FactTransaksi::whereDate('tanggal_masuk', $date->format('Y-m-d'))
                    ->distinct('id_pemilik')
                    ->count('id_pemilik');
                
                $bookingChartData['labels'][] = $date->format('d M');
                $bookingChartData['bookings'][] = $bookings;
                $bookingChartData['customers'][] = $customers;
            }
        } else {
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $bookings = FactTransaksi::whereMonth('tanggal_masuk', $month->month)
                    ->whereYear('tanggal_masuk', $month->year)
                    ->count();
                
                $customers = FactTransaksi::whereMonth('tanggal_masuk', $month->month)
                    ->whereYear('tanggal_masuk', $month->year)
                    ->distinct('id_pemilik')
                    ->count('id_pemilik');
                
                $bookingChartData['labels'][] = $month->format('M Y');
                $bookingChartData['bookings'][] = $bookings;
                $bookingChartData['customers'][] = $customers;
            }
        }

        // Service Performance - Aggregate FactTransaksi and join with operational PaketLayanan
        $allPaketLayanan = PaketLayanan::where('is_active', true)->get();
        
        // Aggregate FactTransaksi by id_paket (not paket_key)
        $currentServicePerformance = FactTransaksi::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->where('status_pembayaran', 'lunas')
            ->select(
                'id_paket',
                DB::raw('SUM(total_biaya) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->groupBy('id_paket')
            ->get()
            ->keyBy('id_paket');

        $servicePerformance = $allPaketLayanan->map(function ($paket) use ($currentServicePerformance) {
            $current = $currentServicePerformance->get($paket->id_paket);
            
            return (object)[
                'name' => $paket->nama_paket,
                'revenue' => $current ? $current->revenue : 0,
                'bookings' => $current ? $current->bookings : 0,
            ];
        })->values();

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'totalRevenue' => $totalRevenue,
                'totalBookings' => $totalBookings,
                'activeCustomers' => $activeCustomers,
                'revenueChartData' => $revenueChartData,
                'bookingChartData' => $bookingChartData,
                'servicePerformance' => $servicePerformance,
                'topCustomers' => $topCustomers
            ]);
        }

        // Regular view response
        return view('admin.reports', compact(
            'totalRevenue',
            'totalBookings',
            'activeCustomers',
            'revenueChartData',
            'bookingChartData',
            'servicePerformance',
            'topCustomers'
        ));
    }
}

