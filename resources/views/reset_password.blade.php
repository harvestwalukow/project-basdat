@extends('layouts.app')

@section('title', 'Reset Password - PawsHotel')

@section('body-class', 'bg-blue-50 min-h-screen')

@section('content')
  <div class="flex items-center justify-center p-4 py-16">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-semibold text-center mb-6">Reset Password</h1>

    <!-- Pesan sukses atau error -->
    @if(session('success'))
      <p class="text-green-600 text-sm text-center mb-3">{{ session('success') }}</p>
    @elseif(session('error'))
      <p class="text-red-600 text-sm text-center mb-3">{{ session('error') }}</p>
    @endif

    <form action="{{ route('password.reset.submit') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label for="email" class="block text-sm font-medium">Email</label>
        <input type="email" id="email" name="email" required placeholder="Masukkan email akun Anda"
          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium">Password Baru</label>
        <input type="password" id="password" name="password" required placeholder="Password baru"
          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Password Baru</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru"
          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
      </div>

      <button type="submit" class="w-full bg-[#F2784B] hover:bg-[#e0673d] text-white py-2 rounded-lg font-semibold">
        Simpan Password Baru
      </button>

      <p class="text-center text-sm text-gray-600 mt-3">
        <a href="{{ route('signin') }}" class="text-blue-600 hover:underline">Kembali ke Sign In</a>
      </p>
    </form>
  </div>
  </div>
@endsection
