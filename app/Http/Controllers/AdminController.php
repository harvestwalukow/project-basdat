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

        // Get monthly revenue (last 12 months)
        $monthlyRevenue = Pembayaran::where('status_pembayaran', 'lunas')
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

        // Calculate room capacity based on active bookings (by pet type and package type)
        $aktivePenitipans = Penitipan::with(['hewan', 'detailPenitipan.paketLayanan'])
            ->where('status', 'aktif')
            ->get();

        // Count rooms by package type and pet type
        $premiumKucingUsed = 0;
        $basicKucingUsed = 0;
        $premiumAnjingUsed = 0;
        $basicAnjingUsed = 0;
        
        foreach ($aktivePenitipans as $penitipan) {
            $jenisHewan = strtolower($penitipan->hewan->jenis_hewan ?? '');
            
            foreach ($penitipan->detailPenitipan as $detail) {
                if ($detail->paketLayanan) {
                    $namaPacket = strtolower($detail->paketLayanan->nama_paket);
                    
                    // Check if it's a main package (not add-on)
                    if (str_contains($namaPacket, 'paket')) {
                        // Determine pet type
                        $isKucing = str_contains($jenisHewan, 'kucing') || str_contains($jenisHewan, 'cat');
                        $isAnjing = str_contains($jenisHewan, 'anjing') || str_contains($jenisHewan, 'dog');
                        
                        // Count based on package type and pet type
                        if (str_contains($namaPacket, 'premium')) {
                            if ($isKucing) {
                                $premiumKucingUsed++;
                            } elseif ($isAnjing) {
                                $premiumAnjingUsed++;
                            }
                        } elseif (str_contains($namaPacket, 'basic')) {
                            if ($isKucing) {
                                $basicKucingUsed++;
                            } elseif ($isAnjing) {
                                $basicAnjingUsed++;
                            }
                        }
                        // Only count once per penitipan (main package)
                        break;
                    }
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
        // Get all pets with owner and penitipan information
        $hewans = Hewan::with(['pemilik', 'penitipan' => function($query) {
            // Sort penitipan by tanggal_masuk to get the latest one first
            $query->orderBy('tanggal_masuk', 'desc');
        }, 'penitipan.detailPenitipan.paketLayanan'])
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
        // Get all staff and admin users from pengguna table
        $employees = Pengguna::whereIn('role', ['staff', 'admin'])
            ->withCount(['staffPenitipans', 'updateKondisis'])
            ->orderBy('created_at', 'desc')
            ->get();

        $employees = $employees->map(function($emp) {
            // Map specialization to department and position
            $specialization = $emp->specialization;
            
            // Only process staff with valid specialization
            if (!in_array($specialization, ['groomer', 'handler', 'trainer'])) {
                return null; // Skip invalid/null specializations
            }
            
            switch ($specialization) {
                case 'groomer':
                    $department = 'Groomer';
                    $position = 'Pet Groomer';
                    $specializationName = 'Grooming & Perawatan';
                    break;
                case 'handler':
                    $department = 'Handler';
                    $position = 'Pet Handler';
                    $specializationName = 'Penanganan Hewan & Pick-up/Delivery';
                    break;
                case 'trainer':
                    $department = 'Trainer';
                    $position = 'Pet Trainer';
                    $specializationName = 'Pelatihan Hewan';
                    break;
            }
            
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
                'experience' => Carbon::parse($emp->created_at)->diffInMonths(now()) . ' bulan',
                'joinDate' => Carbon::parse($emp->created_at)->format('d M Y'),
                'rating' => 4.5,
                'salary' => 5000000,
                'bonus' => $emp->staff_penitipans_count * 100000 + $emp->update_kondisis_count * 50000,
                'task_count' => $emp->staff_penitipans_count + $emp->update_kondisis_count
            ];
        })->filter()->values()->toArray(); // Filter out nulls and reindex

        // Department Stats - Count by specialization
        $groomerCount = count(array_filter($employees, fn($e) => $e['department'] === 'Groomer'));
        $handlerCount = count(array_filter($employees, fn($e) => $e['department'] === 'Handler'));
        $trainerCount = count(array_filter($employees, fn($e) => $e['department'] === 'Trainer'));
        
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

        // Total Revenue
        $totalRevenue = DB::table('pembayaran')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        // Total Bookings
        $totalBookings = DB::table('penitipan')
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->count();

        // Active Customers
        $activeCustomers = DB::table('penitipan')
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->distinct('id_pemilik')
            ->count('id_pemilik');

        // Revenue Chart Data
        $revenueChartData = [
            'labels' => [],
            'data' => []
        ];

        if ($periodType === 'day') {
            // Show daily data for last 30 days
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $revenue = DB::table('pembayaran')
                    ->whereDate('tanggal_bayar', $date->format('Y-m-d'))
                    ->where('status_pembayaran', 'lunas')
                    ->sum('jumlah_bayar');
                
                $revenueChartData['labels'][] = $date->format('d M');
                $revenueChartData['data'][] = round($revenue / 1000000, 2);
            }
        } else {
            // Show monthly data
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $revenue = DB::table('pembayaran')
                    ->whereMonth('tanggal_bayar', $month->month)
                    ->whereYear('tanggal_bayar', $month->year)
                    ->where('status_pembayaran', 'lunas')
                    ->sum('jumlah_bayar');
                
                $revenueChartData['labels'][] = $month->format('M Y');
                $revenueChartData['data'][] = round($revenue / 1000000, 2);
            }
        }

        // Booking Chart Data
        $bookingChartData = [
            'labels' => [],
            'bookings' => [],
            'customers' => []
        ];

        if ($periodType === 'day') {
            // Show daily data for last 30 days
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $bookings = DB::table('penitipan')
                    ->whereDate('tanggal_masuk', $date->format('Y-m-d'))
                    ->count();
                
                $customers = DB::table('penitipan')
                    ->whereDate('tanggal_masuk', $date->format('Y-m-d'))
                    ->distinct('id_pemilik')
                    ->count('id_pemilik');
                
                $bookingChartData['labels'][] = $date->format('d M');
                $bookingChartData['bookings'][] = $bookings;
                $bookingChartData['customers'][] = $customers;
            }
        } else {
            // Show monthly data
            for ($i = $periodCount - 1; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $bookings = DB::table('penitipan')
                    ->whereMonth('tanggal_masuk', $month->month)
                    ->whereYear('tanggal_masuk', $month->year)
                    ->count();
                
                $customers = DB::table('penitipan')
                    ->whereMonth('tanggal_masuk', $month->month)
                    ->whereYear('tanggal_masuk', $month->year)
                    ->distinct('id_pemilik')
                    ->count('id_pemilik');
                
                $bookingChartData['labels'][] = $month->format('M Y');
                $bookingChartData['bookings'][] = $bookings;
                $bookingChartData['customers'][] = $customers;
            }
        }

        // Service Performance - All packages (Basic, Premium, and Add-ons)
        $allPaketLayanan = DB::table('paket_layanan')
            ->where('is_active', true)
            ->select('id_paket', 'nama_paket')
            ->orderByRaw("CASE 
                WHEN nama_paket LIKE '%Paket Basic%' THEN 1 
                WHEN nama_paket LIKE '%Paket Premium%' THEN 2 
                WHEN nama_paket LIKE '%Grooming%' THEN 3
                WHEN nama_paket LIKE '%Kolam%' THEN 4
                WHEN nama_paket LIKE '%Pick%' THEN 5
                WHEN nama_paket LIKE '%Enrichment%' THEN 6
                ELSE 7 END")
            ->get()
            ->keyBy('id_paket');

        $currentServicePerformance = DB::table('detail_penitipan')
            ->join('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->join('penitipan', 'detail_penitipan.id_penitipan', '=', 'penitipan.id_penitipan')
            ->join('pembayaran', 'penitipan.id_penitipan', '=', 'pembayaran.id_penitipan')
            ->whereBetween('penitipan.created_at', [$startDate, $endDate])
            ->where('pembayaran.status_pembayaran', 'lunas')
            ->where('paket_layanan.is_active', true)
            ->select(
                'paket_layanan.id_paket',
                DB::raw('SUM(detail_penitipan.subtotal) as revenue'),
                DB::raw('COUNT(DISTINCT detail_penitipan.id_detail) as bookings')
            )
            ->groupBy('paket_layanan.id_paket')
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
                'servicePerformance' => $servicePerformance
            ]);
        }

        // Regular view response
        return view('admin.reports', compact(
            'totalRevenue',
            'totalBookings',
            'activeCustomers',
            'revenueChartData',
            'bookingChartData',
            'servicePerformance'
        ));
    }
}

