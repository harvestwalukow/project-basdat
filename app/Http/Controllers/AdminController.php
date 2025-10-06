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

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Get statistics
        $totalPenitipanAktif = Penitipan::where('status', 'aktif')->count();
        $totalHewan = Hewan::count();
        $totalPengguna = Pengguna::where('role', 'pet_owner')->count();

        // Get weekly revenue (last 7 days)
        $weeklyRevenue = Pembayaran::where('status_pembayaran', 'lunas')
            ->where('tanggal_bayar', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(tanggal_bayar) as date'),
                DB::raw('SUM(jumlah_bayar) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare weekly revenue data for chart
        $revenueData = [];
        $revenueLabels = [];
        
        // Indonesian day names
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Use Indonesian day names
            $revenueLabels[] = $dayNames[$date->dayOfWeek] . ', ' . $date->format('d M');
            
            $dayRevenue = $weeklyRevenue->firstWhere('date', $date->format('Y-m-d'));
            $revenueData[] = $dayRevenue ? (float) $dayRevenue->total : 0;
        }

        // Get today's schedule (penitipan that start or end today)
        $today = Carbon::today();
        $todaySchedule = Penitipan::with(['hewan', 'pemilik'])
            ->where(function($query) use ($today) {
                $query->whereDate('tanggal_masuk', $today)
                      ->orWhereDate('tanggal_keluar', $today);
            })
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal_masuk')
            ->get();

        // Get latest updates (kondisi updates)
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
        // Get all penitipan with relationships
        $penitipans = Penitipan::with(['hewan', 'pemilik', 'staff', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalPenitipan = $penitipans->count();
        $aktifCount = $penitipans->where('status', 'aktif')->count();
        $selesaiCount = $penitipans->where('status', 'selesai')->count();

        return view('admin.booking', compact(
            'penitipans',
            'totalPenitipan',
            'aktifCount',
            'selesaiCount'
        ));
    }

    /**
     * Pets Management
     */
    public function pets()
    {
        // Get all pets with owner and penitipan information
        $hewans = Hewan::with(['pemilik', 'penitipan' => function($query) {
            // Sort penitipan by tanggal_masuk to get the latest one first
            $query->orderBy('tanggal_masuk', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate statistics directly from the database for accuracy
        $totalHewan = Hewan::count();
        $anjingCount = Hewan::whereRaw('LOWER(jenis_hewan) IN (?, ?)', ['anjing', 'dog'])->count();
        $kucingCount = Hewan::whereRaw('LOWER(jenis_hewan) IN (?, ?)', ['kucing', 'cat'])->count();

        return view('admin.pets', compact(
            'hewans',
            'totalHewan',
            'anjingCount',
            'kucingCount'
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
     * Paket Layanan Management
     */
    public function service()
    {
        // Get all paket layanan
        $paketLayanans = PaketLayanan::withCount('detailPenitipan')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalPaket = $paketLayanans->count();
        $paketAktif = $paketLayanans->where('is_active', true)->count();
        $totalPemesanan = DetailPenitipan::count();

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
        // Get all payments with relationships
        $pembayarans = Pembayaran::with(['penitipan.hewan', 'penitipan.pemilik'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalPendapatan = Pembayaran::where('status_pembayaran', 'lunas')->sum('jumlah_bayar');
        $totalPembayaran = $pembayarans->count();

        // Payment method statistics
        $paymentMethodStats = Pembayaran::where('status_pembayaran', 'lunas')
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

        // Daily revenue for last 7 days
        $dailyRevenue = Pembayaran::where('status_pembayaran', 'lunas')
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
}

