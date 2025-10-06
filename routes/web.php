<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Http\Controllers\PenitipanController;

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
    return view('reservasi', ['user' => auth()->user()]);
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
            'password' => '123456',
            'role' => 'admin',
            'redirect' => '/admin/',
        ],
        'owner@gmail.com' => [
            'password' => '123456',
            'role' => 'owner',
            'redirect' => '/owner',
        ],
        'user@gmail.com' => [
            'password' => '123456',
            'role' => 'user',
            'redirect' => '/dashboard',
        ],
    ];

    // Cek dulu apakah email cocok dengan manual user
    if (isset($manualUsers[$email]) && $manualUsers[$email]['password'] === $password) {
        session([
            'user_email' => $email,
            'user_role' => $manualUsers[$email]['role'],
        ]);
        return redirect($manualUsers[$email]['redirect'])->with('success', 'Berhasil login!');
    }

    // --- LOGIN VIA DATABASE (untuk akun yang daftar lewat signup) ---
    $user = DB::table('users')->where('email', $email)->first();

    if ($user && password_verify($password, $user->password)) {
        session([
            'user_email' => $user->email,
            'user_role' => 'user',
        ]);
        return redirect('/dashboard')->with('success', 'Berhasil login!');
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
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Update password di database
    DB::table('users')
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
    Route::get('/admin/', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    Route::get('/admin/penitipan', function () { return view('admin.booking'); })->name('admin.booking');
    Route::get('/admin/hewan', function () { return view('admin.pets'); })->name('admin.pets');
    Route::get('/admin/update-kondisi', function () { return view('admin.rooms'); })->name('admin.rooms');
    Route::get('/admin/paket-layanan', function () { return view('admin.service'); })->name('admin.service');
    Route::get('/admin/pembayaran', function () { return view('admin.payments'); })->name('admin.payments');
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
        $user = User::where('email', session('user_email'))->first();
        return view('reservasi', ['user' => $user]);
    })->name('dashboard');
});

// Proses Sign Up
Route::post('/signup', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Simpan ke database (pastikan tabel 'users' ada)
    DB::table('users')->insert([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Setelah daftar, langsung login manual ke sesi
    session([
        'user_email' => $request->email,
        'user_role' => 'user',
    ]);

    return redirect('/dashboard')->with('success', 'Akun berhasil dibuat dan Anda telah login!');
})->name('signup.submit');