@extends('admin.layouts.app')

@section('content')
<div class="flex flex-col h-full">
  <!-- Header -->
  <header class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-3xl font-bold">PAKET LAYANAN</h1>
      <p class="text-gray-600">Kelola semua paket layanan yang ditawarkan</p>
    </div>
    <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      <span>Tambah Paket</span>
    </button>
  </header>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Paket</h3>
        <p class="text-3xl font-bold mt-2">3</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Paket Aktif</h3>
        <p class="text-3xl font-bold mt-2">2</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Pemesanan</h3>
        <p class="text-3xl font-bold mt-2">45</p>
    </div>
  </div>

  <!-- Filters -->
  <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex flex-wrap items-center gap-4">
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Tipe</option>
        <option>Paket</option>
        <option>Tambahan</option>
      </select>
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Status</option>
        <option>Aktif</option>
        <option>Non-Aktif</option>
      </select>
    </div>
    <div class="flex bg-gray-200 rounded-lg p-1">
      <button class="px-4 py-2 text-sm font-semibold text-gray-500">Grid</button>
      <button class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white rounded-md shadow">List</button>
    </div>
  </div>

  <!-- Package List Table -->
  <div class="flex-1 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="font-semibold">DAFTAR PAKET LAYANAN</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-max">
        <thead class="bg-gray-50 text-left text-sm text-gray-600">
          <tr>
            <th class="p-4">Nama Paket</th>
            <th class="p-4">Deskripsi</th>
            <th class="p-4">Harga per Hari</th>
            <th class="p-4 text-center">Total Pemesanan</th>
            <th class="p-4 text-center">Status</th>
            <th class="p-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-medium">Paket Basic</td>
            <td class="p-4">Penitipan standar dengan fasilitas dasar</td>
            <td class="p-4">Rp 150.000</td>
            <td class="p-4 text-center">25</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><a href="#" class="text-blue-600 hover:underline">Edit</a></td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-medium">Paket Premium</td>
            <td class="p-4">Penitipan premium dengan fasilitas lengkap</td>
            <td class="p-4">Rp 250.000</td>
            <td class="p-4 text-center">15</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><a href="#" class="text-blue-600 hover:underline">Edit</a></td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-medium">Paket Deluxe</td>
            <td class="p-4">Penitipan mewah dengan layanan eksklusif</td>
            <td class="p-4">Rp 350.000</td>
            <td class="p-4 text-center">5</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Non-Aktif</span></td>
            <td class="p-4"><a href="#" class="text-blue-600 hover:underline">Edit</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
