<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
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
    return view('reservasi');
})->name('reservasi.form');

Route::post('/reservasi', function (Request $request) {
    $data = $request->all(); // ambil semua input
    return view('pembayaran', compact('data'));
})->name('reservasi.submit');

// Auth Pages
Route::get('/signin', function () {
    return view('signin'); // buat file resources/views/auth/signin.blade.php
})->name('signin');

// ✅ Proses Sign In (POST form login)
Route::post('/signin', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    // akun dummy (biar bisa login tanpa sign up)
    $validEmail = 'admin@gmail.com';
    $validPassword = '123456';

    if ($email === $validEmail && $password === $validPassword) {
        return redirect('/dashboard')->with('success', 'Berhasil login!');
    } else {
        return back()->with('error', 'Email atau password salah');
    }
})->name('signin.submit');

// ✅ Dashboard (setelah login berhasil)
Route::get('/dashboard', function () {
    return view('dashboard'); // buat file resources/views/dashboard.blade.php
})->name('dashboard');

Route::get('/signup', function () {
    return view('signup'); // buat file resources/views/auth/signup.blade.php
})->name('signup');
