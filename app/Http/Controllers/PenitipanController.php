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
        $validated = $request->validate([
            'ownerName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'petName' => 'required|string|max:255',
            'petType' => 'required|in:anjing,kucing',
            'petBreed' => 'nullable|string|max:255',
            'petAge' => 'nullable|integer|min:0',
            'petWeight' => 'nullable|numeric|min:0',
            'packageType' => 'required|string',
            'packageId' => 'required|exists:paket_layanan,id_paket',
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after:checkInDate',
            'specialRequests' => 'nullable|string',
        ], [
            'packageType.required' => 'Silakan pilih paket layanan',
            'packageId.required' => 'Silakan pilih paket layanan',
            'packageId.exists' => 'Paket layanan tidak valid',
            'petType.in' => 'Jenis hewan harus anjing atau kucing',
            'checkInDate.after_or_equal' => 'Tanggal check-in tidak boleh di masa lalu',
            'checkOutDate.after' => 'Tanggal check-out harus setelah check-in',
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

            // 3. Get package details from database
            $paketLayanan = DB::table('paket_layanan')
                ->where('id_paket', $request->packageId)
                ->first();
            
            if (!$paketLayanan) {
                throw new \Exception('Paket layanan tidak ditemukan');
            }
            
            $hargaPaket = $paketLayanan->harga_per_hari;
            $totalBiaya = $hargaPaket * $jumlahHari;

            // 4. Create penitipan record
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

            // 5. Create detail_penitipan record for main package
            DB::table('detail_penitipan')->insert([
                'id_penitipan' => $penitipanId,
                'id_paket' => $request->packageId,
                'jumlah_hari' => $jumlahHari,
                'subtotal' => $totalBiaya,
                'created_at' => now(),
            ]);

            // 6. Process layanan tambahan (add-ons)
            $totalLayananTambahan = 0;
            foreach ($request->all() as $key => $value) {
                // Check if this is an addon field (addon_X where X is id_paket)
                if (strpos($key, 'addon_') === 0 && $value > 0) {
                    $addonId = str_replace('addon_', '', $key);
                    $jumlahAddon = (int) $value;
                    
                    // Get addon price
                    $addon = DB::table('paket_layanan')
                        ->where('id_paket', $addonId)
                        ->first();
                    if ($addon) {
                        $subtotalAddon = $addon->harga_per_hari * $jumlahAddon;
                        $totalLayananTambahan += $subtotalAddon;
                        
                        // Insert detail_penitipan for this addon
                        DB::table('detail_penitipan')->insert([
                            'id_penitipan' => $penitipanId,
                            'id_paket' => $addonId,
                            'jumlah_hari' => $jumlahAddon, // For addons, jumlah_hari represents quantity
                            'subtotal' => $subtotalAddon,
                            'created_at' => now(),
                        ]);
                    }
                }
            }
            
            // Update total_biaya in penitipan if there are add-ons
            if ($totalLayananTambahan > 0) {
                $totalBiaya += $totalLayananTambahan;
                DB::table('penitipan')
                    ->where('id_penitipan', $penitipanId)
                    ->update(['total_biaya' => $totalBiaya]);
            }

            // 7. Create pembayaran record
            $nomorTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad($penitipanId, 6, '0', STR_PAD_LEFT);
            
            DB::table('pembayaran')->insert([
                'id_penitipan' => $penitipanId,
                'nomor_transaksi' => $nomorTransaksi,
                'jumlah_bayar' => $totalBiaya,
                'metode_pembayaran' => 'cash', // Default cash, can be changed by admin
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

