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
        // Get all pets with owner information
        $hewans = Hewan::with(['pemilik', 'penitipan' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate statistics
        $totalHewan = $hewans->count();
        $anjingCount = $hewans->where('jenis_hewan', 'anjing')->count();
        $kucingCount = $hewans->where('jenis_hewan', 'kucing')->count();

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

        return view('admin.rooms', compact(
            'updateKondisis',
            'sehatCount',
            'perluPerhatianCount'
        ));
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
}

