<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

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

Route::get('/reservasi', function () {
    return view('reservasi');
})->name('reservasi');


Route::get('/reservasi', function () {
    return view('reservasi');
})->name('reservasi.form');

Route::post('/reservasi', function (Request $request) {
    $data = $request->all(); // ambil semua input
    return view('pembayaran', compact('data'));
})->name('reservasi.submit');


