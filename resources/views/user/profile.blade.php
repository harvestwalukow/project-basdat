@extends('layouts.user')

@section('title', 'Profile Settings - PawsHotel')

@section('body-class', 'bg-[#FEFBF7] text-[#333333]')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <!-- Profile Header -->
  <div class="bg-gradient-to-r from-orange-400 to-orange-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
    <div class="flex items-center space-x-4">
      <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-[#F2784B] text-3xl font-bold">
        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
      </div>
      <div>
        <h1 class="text-3xl font-bold mb-1">Profile Settings</h1>
        <p class="text-orange-100">Kelola informasi akun dan preferensi Anda</p>
      </div>
    </div>
  </div>

  <!-- Profile Information -->
  <div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
      <span class="text-2xl mr-2">👤</span> Informasi Akun
    </h2>
    
    <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
          <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none" required>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
          <input type="email" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none" required>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
          <input type="text" id="phone" name="phone" value="" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none" 
                 placeholder="08xxxxxxxxxx">
        </div>

        <div>
          <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
          <input type="date" id="birthdate" name="birthdate" value="" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none">
        </div>
      </div>

      <div>
        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
        <textarea id="address" name="address" rows="3" 
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none" 
                  placeholder="Masukkan alamat lengkap Anda"></textarea>
      </div>

      <div class="flex justify-end space-x-3 pt-4 border-t">
        <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
          Batal
        </button>
        <button type="submit" class="px-6 py-2 bg-[#F2784B] hover:bg-[#e0673d] text-white rounded-lg font-semibold">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  <!-- Change Password -->
  <div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
      <span class="text-2xl mr-2">🔒</span> Ubah Password
    </h2>
    
    <form action="{{ route('user.password.update') }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini *</label>
        <input type="password" id="current_password" name="current_password" 
               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none" required>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru *</label>
          <input type="password" id="new_password" name="new_password" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none" 
                 minlength="6" required>
          <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
        </div>

        <div>
          <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru *</label>
          <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#F2784B] focus:outline-none" 
                 minlength="6" required>
        </div>
      </div>

      <div class="flex justify-end pt-4 border-t">
        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
          Update Password
        </button>
      </div>
    </form>
  </div>

  <!-- Notification Preferences -->
  <div class="bg-white rounded-xl shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
      <span class="text-2xl mr-2">🔔</span> Preferensi Notifikasi
    </h2>
    
    <div class="space-y-3">
      <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
        <div class="flex items-center space-x-3">
          <span class="text-xl">📧</span>
          <div>
            <p class="font-medium text-gray-800">Email Notifications</p>
            <p class="text-sm text-gray-500">Terima update reservasi via email</p>
          </div>
        </div>
        <input type="checkbox" class="w-5 h-5 text-[#F2784B] rounded focus:ring-[#F2784B]" checked>
      </label>

      <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
        <div class="flex items-center space-x-3">
          <span class="text-xl">💬</span>
          <div>
            <p class="font-medium text-gray-800">WhatsApp Notifications</p>
            <p class="text-sm text-gray-500">Terima update hewan via WhatsApp</p>
          </div>
        </div>
        <input type="checkbox" class="w-5 h-5 text-[#F2784B] rounded focus:ring-[#F2784B]" checked>
      </label>

      <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
        <div class="flex items-center space-x-3">
          <span class="text-xl">📢</span>
          <div>
            <p class="font-medium text-gray-800">Promotional Updates</p>
            <p class="text-sm text-gray-500">Terima info promo dan diskon</p>
          </div>
        </div>
        <input type="checkbox" class="w-5 h-5 text-[#F2784B] rounded focus:ring-[#F2784B]">
      </label>
    </div>
  </div>

  <!-- Danger Zone -->
  <div class="mt-8 bg-red-50 border border-red-200 rounded-xl p-6">
    <h2 class="text-xl font-bold text-red-800 mb-2 flex items-center">
      <span class="text-2xl mr-2">⚠️</span> Danger Zone
    </h2>
    <p class="text-sm text-red-600 mb-4">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan</p>
    <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">
      Hapus Akun
    </button>
  </div>
</div>
@endsection

