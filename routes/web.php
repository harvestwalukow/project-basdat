<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Pengguna;
use App\Http\Controllers\PenitipanController;
use App\Http\Controllers\AdminController;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes-db', function () {
    try {
        $tables = DB::select('SHOW TABLES');
        $tableNames = array_map('current', json_decode(json_encode($tables), true));
        return 'Daftar tabel di er_basdat: ' . implode(', ', $tableNames);
    } catch (\Exception $e) {
        return 'Gagal terhubung ke database. Error: ' . $e->getMessage();
    }
});

// Halaman Statis
Route::get('/about', function () {
    return view('about');
});

Route::get('/fasilitas', function () {
    return view('fasilitas');
});

Route::get('/kontak', function () {
    return view('kontak');
});

Route::get('/layanan', function () {
    return view('layanan');
});

// Reservasi
Route::get('/reservasi', function () {
    $user = session('user_email') ? Pengguna::where('email', session('user_email'))->first() : null;
    return view('user.reservasi', ['user' => $user]);
})->name('reservasi');

Route::post('/reservasi', [PenitipanController::class, 'store'])->name('reservasi.submit');

// Auth Pages
Route::get('/signin', function () {
    return view('signin');
})->name('signin');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

// Proses Sign In (Gabungan: Manual + Database)
Route::post('/signin', function (Request $request) {
    $email = trim($request->input('email'));
    $password = trim($request->input('password'));

    // --- LOGIN MANUAL: ADMIN, OWNER, USER ---
    $manualUsers = [
        'admin@gmail.com' => [
            'id' => 9999,
            'name' => 'Admin',
            'password' => '123456',
            'role' => 'admin',
            'redirect' => '/admin/',
        ],
        'owner@gmail.com' => [
            'id' => 9998,
            'name' => 'Owner',
            'password' => '123456',
            'role' => 'owner',
            'redirect' => '/owner',
        ],
        'user@gmail.com' => [
            'id' => 9997,
            'name' => 'User',
            'password' => '123456',
            'role' => 'user',
            'redirect' => '/dashboard',
        ],
    ];

    // Cek dulu apakah email cocok dengan manual user
    if (isset($manualUsers[$email]) && $manualUsers[$email]['password'] === $password) {
        session([
            'user_id' => $manualUsers[$email]['id'],
            'user_email' => $email,
            'user_name' => $manualUsers[$email]['name'],
            'user_role' => $manualUsers[$email]['role'],
        ]);
        return redirect($manualUsers[$email]['redirect'])->with('success', 'Berhasil login!');
    }

    // --- LOGIN VIA DATABASE (untuk akun yang daftar lewat signup) ---
    $user = DB::table('pengguna')->where('email', $email)->first();

    if ($user && password_verify($password, $user->password)) {
        session([
            'user_id' => $user->id_pengguna,
            'user_email' => $user->email,
            'user_name' => $user->nama_lengkap,
            'user_role' => $user->role,
        ]);
        
        // Redirect berdasarkan role
        $redirectMap = [
            'admin' => '/admin/',
            'staff' => '/admin/',
            'pet_owner' => '/dashboard',
        ];
        
        $redirect = $redirectMap[$user->role] ?? '/dashboard';
        return redirect($redirect)->with('success', 'Berhasil login!');
    }

    // Kalau dua-duanya gagal
    return back()->with('error', 'Email atau password salah');
})->name('signin.submit');

// Reset Password (Lupa Password)
Route::get('/reset-password', function () {
    return view('reset_password');
})->name('password.reset.form');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email|exists:pengguna,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Update password di database
    DB::table('pengguna')
        ->where('email', $request->email)
        ->update([
            'password' => bcrypt($request->password),
            'updated_at' => now(),
        ]);

    return redirect()->route('signin')->with('success', 'Password berhasil diperbarui! Silakan login dengan password baru.');
})->name('password.reset.submit');

// Logout
Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('signin')->with('success', 'Anda telah berhasil logout.');
})->name('logout');


// Protected Routes - Admin
Route::middleware('admin')->group(function () {
    Route::get('/admin/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/penitipan', [AdminController::class, 'booking'])->name('admin.booking');
    Route::get('/admin/hewan', [AdminController::class, 'pets'])->name('admin.pets');
    Route::get('/admin/update-kondisi', [AdminController::class, 'rooms'])->name('admin.rooms');
    Route::get('/admin/paket-layanan', [AdminController::class, 'service'])->name('admin.service');
    Route::get('/admin/pembayaran', [AdminController::class, 'payments'])->name('admin.payments');
    Route::put('/admin/pembayaran/{id}/update-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.payments.update');
});


// Protected Routes - Owner
Route::middleware('owner')->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', function () { return view('owner.dashboard'); })->name('dashboard');

    Route::get('/reservations/{tab?}', function ($tab = 'semua') {
    $validTabs = ['semua', 'today', 'upcoming', 'selesai'];
    if (!in_array($tab, $validTabs)) {
        $tab = 'semua';
    }
    return view('owner.reservations', compact('tab')); // BUKAN owner.reservations.index
})->name('reservations');
    Route::get('/finance', function () { return view('owner.finance'); })->name('finance');
    Route::get('/pets', function () { return view('owner.pets'); })->name('pets');
    Route::get('/services', function () { return view('owner.services'); })->name('services');
    Route::get('/staff', function () { return view('owner.staff'); })->name('staff');
    Route::get('/reports', function () { return view('owner.reports'); })->name('reports');
});



// Protected Routes - User (Pelanggan)
Route::middleware('user')->group(function () {
    Route::get('/dashboard', function () {
        $userId = session('user_id');
        $user = Pengguna::find($userId);
        
        if (!$user) {
            return redirect()->route('signin')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get all reservations for this user with related data
        $reservations = DB::table('penitipan')
            ->leftJoin('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->leftJoin('pembayaran', 'penitipan.id_penitipan', '=', 'pembayaran.id_penitipan')
            ->leftJoin('detail_penitipan', 'penitipan.id_penitipan', '=', 'detail_penitipan.id_penitipan')
            ->leftJoin('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->where('penitipan.id_pemilik', $userId)
            ->select(
                'penitipan.*',
                'hewan.nama_hewan',
                'hewan.jenis_hewan',
                'hewan.ras',
                'pembayaran.status_pembayaran',
                'pembayaran.nomor_transaksi',
                'paket_layanan.nama_paket'
            )
            ->orderBy('penitipan.created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total' => $reservations->count(),
            'aktif' => $reservations->where('status', 'aktif')->count(),
            'hewan' => DB::table('hewan')->where('id_pemilik', $userId)->count(),
        ];

        return view('user.dashboard', compact('user', 'reservations', 'stats'));
    })->name('dashboard');
});

// Proses Sign Up
Route::post('/signup', function (Request $request) {
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email',
        'password' => 'required|string|min:6|confirmed',
        'no_telepon' => 'required|string|max:20',
        'alamat' => 'required|string',
    ]);

    // Simpan ke database tabel pengguna
    $userId = DB::table('pengguna')->insertGetId([
        'nama_lengkap' => $request->nama_lengkap,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'no_telepon' => $request->no_telepon,
        'alamat' => $request->alamat,
        'role' => 'pet_owner', // Auto set role to pet_owner
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Setelah daftar, langsung login manual ke sesi
    session([
        'user_id' => $userId,
        'user_email' => $request->email,
        'user_name' => $request->nama_lengkap,
        'user_role' => 'pet_owner',
    ]);

    return redirect('/dashboard')->with('success', 'Akun berhasil dibuat dan Anda telah login!');
})->name('signup.submit');