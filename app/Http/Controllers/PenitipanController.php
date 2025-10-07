<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengguna;
use Midtrans\Config;
use Midtrans\Snap;

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
            'packageId' => 'required|integer',
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

            // Get package price from database
            $paketLayanan = DB::table('paket_layanan')
                ->where('id_paket', $request->packageId)
                ->first();
            
            if (!$paketLayanan) {
                return back()->with('error', 'Paket layanan tidak ditemukan.')->withInput();
            }
            
            $hargaPaket = $paketLayanan->harga_per_hari;
            $totalBiaya = $hargaPaket * $jumlahHari;
            
            // Add addon costs
            $layananTambahan = DB::table('paket_layanan')
                ->where('is_active', true)
                ->where('nama_paket', 'NOT LIKE', '%Paket%')
                ->get();
            
            foreach ($layananTambahan as $layanan) {
                $addonKey = 'addon_' . $layanan->id_paket;
                if ($request->has($addonKey) && $request->$addonKey > 0) {
                    $totalBiaya += $layanan->harga_per_hari * $request->$addonKey;
                }
            }

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

            // 4. Use the package ID from request
            $paketLayananId = $request->packageId;

            // 5. Create detail_penitipan record for main package
            DB::table('detail_penitipan')->insert([
                'id_penitipan' => $penitipanId,
                'id_paket' => $paketLayananId,
                'jumlah_hari' => $jumlahHari,
                'subtotal' => $hargaPaket * $jumlahHari,
                'created_at' => now(),
            ]);
            
            // Create detail_penitipan for addons
            foreach ($layananTambahan as $layanan) {
                $addonKey = 'addon_' . $layanan->id_paket;
                if ($request->has($addonKey) && $request->$addonKey > 0) {
                    $jumlahAddon = $request->$addonKey;
                    DB::table('detail_penitipan')->insert([
                        'id_penitipan' => $penitipanId,
                        'id_paket' => $layanan->id_paket,
                        'jumlah_hari' => $jumlahAddon,
                        'subtotal' => $layanan->harga_per_hari * $jumlahAddon,
                        'created_at' => now(),
                    ]);
                }
            }

            // 6. Create pembayaran record
            $nomorTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad($penitipanId, 6, '0', STR_PAD_LEFT);
            
            $pembayaranId = DB::table('pembayaran')->insertGetId([
                'id_penitipan' => $penitipanId,
                'nomor_transaksi' => $nomorTransaksi,
                'jumlah_bayar' => $totalBiaya,
                'metode_pembayaran' => 'cash', // Default, will be updated after payment
                'status_pembayaran' => 'pending',
                'tanggal_bayar' => null,
                'bukti_pembayaran' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Configure Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // Build transaction details
            $transactionDetails = [
                'order_id' => $nomorTransaksi,
                'gross_amount' => (int) $totalBiaya,
            ];

            // Build item details
            $itemDetails = [
                [
                    'id' => $paketLayananId,
                    'price' => (int) $hargaPaket,
                    'quantity' => $jumlahHari,
                    'name' => $request->packageType . ' (' . $jumlahHari . ' hari)',
                ]
            ];
            
            // Add addon items
            foreach ($layananTambahan as $layanan) {
                $addonKey = 'addon_' . $layanan->id_paket;
                if ($request->has($addonKey) && $request->$addonKey > 0) {
                    $jumlahAddon = $request->$addonKey;
                    $itemDetails[] = [
                        'id' => $layanan->id_paket,
                        'price' => (int) $layanan->harga_per_hari,
                        'quantity' => $jumlahAddon,
                        'name' => $layanan->nama_paket,
                    ];
                }
            }

            // Customer details
            $customerDetails = [
                'first_name' => $request->ownerName,
                'email' => $request->email ?: $user->email,
                'phone' => $request->phone,
            ];

            // Build transaction data
            $transaction = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
            ];

            try {
                $snapToken = Snap::getSnapToken($transaction);
                
                // Store snap token in session
                session([
                    'snap_token' => $snapToken,
                    'order_id' => $nomorTransaksi,
                    'penitipan_id' => $penitipanId,
                    'pembayaran_id' => $pembayaranId,
                ]);
                
                // Redirect to payment page
                return redirect()->route('payment.page')->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran.');
                
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage())->withInput();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    public function paymentPage()
    {
        $snapToken = session('snap_token');
        $orderId = session('order_id');
        
        if (!$snapToken || !$orderId) {
            return redirect()->route('dashboard')->with('error', 'Data pembayaran tidak ditemukan.');
        }
        
        return view('user.payment', compact('snapToken', 'orderId'));
    }
    
    public function paymentCallback(Request $request)
    {
        // Configure Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        
        try {
            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $grossAmount = $request->gross_amount;
            $transactionStatus = $request->transaction_status;
            $paymentType = $request->payment_type ?? 'cash';
            
            // Map Midtrans payment type to our ENUM values
            $metodeMapping = [
                'credit_card' => 'kartu_kredit',
                'bank_transfer' => 'transfer',
                'echannel' => 'transfer',
                'gopay' => 'e_wallet',
                'shopeepay' => 'e_wallet',
                'qris' => 'qris',
                'qris_dynamic' => 'qris',
                'cstore' => 'cash',
            ];
            $metodePembayaran = $metodeMapping[$paymentType] ?? 'transfer';
            
            // Get payment data
            $pembayaran = DB::table('pembayaran')
                ->where('nomor_transaksi', $orderId)
                ->first();
            
            if (!$pembayaran) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }
            
            // Update payment status based on transaction status
            $paymentStatus = 'pending';
            $penitipanStatus = 'pending';
            
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $paymentStatus = 'success';
                $penitipanStatus = 'aktif';
            } elseif ($transactionStatus == 'pending') {
                $paymentStatus = 'pending';
                $penitipanStatus = 'pending';
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $paymentStatus = 'failed';
                $penitipanStatus = 'dibatalkan';
            }
            
            // Update payment record
            DB::table('pembayaran')
                ->where('nomor_transaksi', $orderId)
                ->update([
                    'status_pembayaran' => $paymentStatus == 'success' ? 'lunas' : ($paymentStatus == 'failed' ? 'gagal' : 'pending'),
                    'metode_pembayaran' => $metodePembayaran,
                    'tanggal_bayar' => $paymentStatus == 'success' ? now() : null,
                    'updated_at' => now(),
                ]);
            
            // Update penitipan status
            DB::table('penitipan')
                ->where('id_penitipan', $pembayaran->id_penitipan)
                ->update([
                    'status' => $penitipanStatus,
                    'updated_at' => now(),
                ]);
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function paymentFinish(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;
        
        try {
            // Update payment status based on transaction result
            if ($orderId && $transactionStatus) {
                $pembayaran = DB::table('pembayaran')
                    ->where('nomor_transaksi', $orderId)
                    ->first();
                
                if ($pembayaran) {
                    // Determine payment status
                    $paymentStatus = 'pending';
                    $penitipanStatus = 'pending';
                    
                    if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                        $paymentStatus = 'lunas';
                        $penitipanStatus = 'aktif';
                    } elseif ($transactionStatus == 'pending') {
                        $paymentStatus = 'pending';
                        $penitipanStatus = 'pending';
                    } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                        $paymentStatus = 'gagal';
                        $penitipanStatus = 'dibatalkan';
                    }
                    
                    // Map payment type if available
                    $paymentType = $request->payment_type;
                    $metodeMapping = [
                        'credit_card' => 'kartu_kredit',
                        'bank_transfer' => 'transfer',
                        'echannel' => 'transfer',
                        'gopay' => 'e_wallet',
                        'shopeepay' => 'e_wallet',
                        'qris' => 'qris',
                        'qris_dynamic' => 'qris',
                        'cstore' => 'cash',
                    ];
                    // Map payment type, default to transfer for unknown types
                    $metodePembayaran = $paymentType ? ($metodeMapping[$paymentType] ?? 'transfer') : 'transfer';
                    
                    // Log untuk debugging (bisa dihapus nanti)
                    \Log::info('Payment Finish - Payment Type: ' . $paymentType);
                    
                    // Update payment record
                    $updateData = [
                        'status_pembayaran' => $paymentStatus,
                        'tanggal_bayar' => $paymentStatus == 'lunas' ? now() : null,
                        'updated_at' => now(),
                    ];
                    
                    // Only update payment method if we have it
                    if ($metodePembayaran) {
                        $updateData['metode_pembayaran'] = $metodePembayaran;
                    }
                    
                    DB::table('pembayaran')
                        ->where('nomor_transaksi', $orderId)
                        ->update($updateData);
                    
                    // Update penitipan status
                    DB::table('penitipan')
                        ->where('id_penitipan', $pembayaran->id_penitipan)
                        ->update([
                            'status' => $penitipanStatus,
                            'updated_at' => now(),
                        ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Payment finish error: ' . $e->getMessage());
        }
        
        // Clear payment session
        session()->forget(['snap_token', 'order_id', 'penitipan_id', 'pembayaran_id']);
        
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil! Reservasi Anda telah dikonfirmasi.');
        } elseif ($transactionStatus == 'pending') {
            return redirect()->route('dashboard')->with('info', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.');
        } else {
            return redirect()->route('dashboard')->with('error', 'Pembayaran gagal atau dibatalkan.');
        }
    }
}

