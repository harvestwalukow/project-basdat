<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengguna;

class PenitipanController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'ownerName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'petName' => 'required|string|max:255',
            'petType' => 'required|string',
            'petBreed' => 'nullable|string|max:255',
            'petAge' => 'nullable|integer',
            'petWeight' => 'nullable|numeric',
            'packageType' => 'required|string',
            'checkInDate' => 'required|date',
            'checkOutDate' => 'required|date|after:checkInDate',
            'specialRequests' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Get the logged-in user ID from session
            $userId = session('user_id');
            
            // If no user is logged in, we need to handle this
            if (!$userId) {
                return redirect()->route('signin')->with('error', 'Silakan login terlebih dahulu untuk melakukan reservasi.');
            }

            // Get user data
            $user = Pengguna::find($userId);
            
            if (!$user) {
                return redirect()->route('signin')->with('error', 'User tidak ditemukan. Silakan login kembali.');
            }

            // 1. Create or find the pet (hewan)
            $hewan = DB::table('hewan')->insertGetId([
                'id_pemilik' => $userId,
                'nama_hewan' => $request->petName,
                'jenis_hewan' => $request->petType,
                'ras' => $request->petBreed ?? '-',
                'umur' => $request->petAge ?? 0,
                'jenis_kelamin' => 'tidak diketahui', // You may want to add this field to the form
                'berat' => $request->petWeight ?? 0,
                'kondisi_khusus' => $request->specialRequests ?? null,
                'catatan_medis' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Calculate days and total cost
            $checkIn = new \DateTime($request->checkInDate);
            $checkOut = new \DateTime($request->checkOutDate);
            $jumlahHari = $checkOut->diff($checkIn)->days;
            if ($jumlahHari < 1) $jumlahHari = 1;

            // Get package price based on package name
            $packagePrices = [
                'Paket Basic' => 150000,
                'Paket Premium' => 250000,
            ];
            
            $hargaPaket = $packagePrices[$request->packageType] ?? 150000;
            $totalBiaya = $hargaPaket * $jumlahHari;

            // 3. Create penitipan record
            $penitipanId = DB::table('penitipan')->insertGetId([
                'id_hewan' => $hewan,
                'id_pemilik' => $userId,
                'id_staff' => null, // Will be assigned later by admin
                'tanggal_masuk' => $request->checkInDate,
                'tanggal_keluar' => $request->checkOutDate,
                'status' => 'pending',
                'catatan_khusus' => $request->specialRequests,
                'total_biaya' => $totalBiaya,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Find or create package in paket_layanan
            $paketLayanan = DB::table('paket_layanan')
                ->where('nama_paket', $request->packageType)
                ->first();

            if (!$paketLayanan) {
                // Create package if it doesn't exist
                $paketLayananId = DB::table('paket_layanan')->insertGetId([
                    'nama_paket' => $request->packageType,
                    'deskripsi' => 'Paket layanan penitipan hewan',
                    'harga_per_hari' => $hargaPaket,
                    'fasilitas' => 'Fasilitas lengkap',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $paketLayananId = $paketLayanan->id_paket;
            }

            // 5. Create detail_penitipan record
            DB::table('detail_penitipan')->insert([
                'id_penitipan' => $penitipanId,
                'id_paket' => $paketLayananId,
                'jumlah_hari' => $jumlahHari,
                'subtotal' => $totalBiaya,
                'created_at' => now(),
            ]);

            // 6. Create pembayaran record
            $nomorTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad($penitipanId, 6, '0', STR_PAD_LEFT);
            
            DB::table('pembayaran')->insert([
                'id_penitipan' => $penitipanId,
                'nomor_transaksi' => $nomorTransaksi,
                'jumlah_bayar' => $totalBiaya,
                'metode_pembayaran' => 'transfer', // Default, can be changed later
                'status_pembayaran' => 'pending',
                'tanggal_bayar' => null,
                'bukti_pembayaran' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Redirect to payment page or success page
            return redirect()->route('dashboard')->with('success', 'Reservasi berhasil dibuat! Nomor transaksi: ' . $nomorTransaksi);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}

