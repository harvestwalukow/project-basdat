@extends('owner.layouts.app')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Manajemen Layanan</h1>
      <p class="text-muted-foreground">Kelola layanan dan paket yang ditawarkan</p>
    </div>
    <button class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path d="M12 4v16m8-8H4"/>
      </svg>
      Tambah Layanan
    </button>
  </div>

  {{-- Category Stats --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Penitipan</p>
          <p class="text-lg font-semibold">2 layanan</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-green-600 font-medium">Rp 45M</p>
          <p class="text-xs text-gray-400">2100 booking</p>
        </div>
      </div>
    </div>
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Grooming</p>
          <p class="text-lg font-semibold">2 layanan</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-green-600 font-medium">Rp 15M</p>
          <p class="text-xs text-gray-400">970 booking</p>
        </div>
      </div>
    </div>
    {{-- Tambah card lainnya sesuai categoryStats --}}
  </div>

  {{-- Tabs --}}
  <div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8">
      <a href="#" class="text-primary border-primary border-b-2 px-1 py-4 text-sm font-medium">Daftar Layanan</a>
      <a href="#" class="text-gray-500 hover:text-gray-700 px-1 py-4 text-sm font-medium">Paket Bundling</a>
      <a href="#" class="text-gray-500 hover:text-gray-700 px-1 py-4 text-sm font-medium">Strategi Harga</a>
      <a href="#" class="text-gray-500 hover:text-gray-700 px-1 py-4 text-sm font-medium">Analitik</a>
    </nav>
  </div>

  {{-- Grid Services --}}
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M20 12H4"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Penitipan Standard</h3>
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Penitipan</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Penitipan harian dengan fasilitas kandang standard, makanan 2x sehari, dan perawatan dasar</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 150.000</p>
          <p class="text-xs text-gray-400">Per hari</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            ⭐ <span class="text-sm font-medium">4.7</span>
          </div>
          <p class="text-xs text-gray-400">1250 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm">🗑</button>
      </div>
    </div>
    {{-- Copy lagi untuk setiap layanan --}}
  </div>
</div>
@endsection
