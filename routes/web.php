<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tentang', function () {
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

Route::get('/reservasi', function () {
    return view('reservasi');
})->name('reservasi');


Route::get('/reservasi', function () {
    return view('reservasi');
})->name('reservasi.form');

Route::post('/reservasi', function (Request $request) {
    // Untuk uji coba, kita lempar semua input ke halaman pembayaran
    return view('pembayaran', [
        'data' => $request->all()
    ]);
})->name('reservasi.submit');


