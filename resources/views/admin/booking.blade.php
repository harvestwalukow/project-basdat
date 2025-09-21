@extends('admin.layouts.app')

@section('content')
  <div class="flex flex-col h-full">
    <!-- Header -->
    <header class="mb-6">
      <h1 class="text-3xl font-bold">Manajemen Penitipan</h1>
      <p class="text-gray-600">Kelola semua penitipan dan reservasi hewan</p>
    </header>

    <!-- Filters -->
    <div class="mb-6">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <!-- View Toggles -->
        <div class="flex bg-gray-200 rounded-lg p-1">
          <button class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white rounded-md shadow">Table View</button>
          <button class="px-4 py-2 text-sm font-semibold text-gray-500">Calendar View</button>
        </div>
        
        <!-- Search and Filters -->
        <div class="flex flex-wrap items-center gap-4">
          <input type="text" placeholder="Cari nama pemilik hewan, ID Penitipan" class="w-full sm:w-64 px-4 py-2 border rounded-lg">
          <select class="px-4 py-2 border rounded-lg">
            <option>Semua Status</option>
            <option>Pending</option>
            <option>Aktif</option>
            <option>Selesai</option>
            <option>Dibatalkan</option>
          </select>
          <input type="date" class="px-4 py-2 border rounded-lg">
        </div>
      </div>
    </div>

    <!-- Penitipan Table -->
    <div class="flex-1 bg-white rounded-lg shadow-md overflow-hidden">
      <div class="px-6 py-4 border-b">
        <h3 class="font-semibold">DAFTAR PENITIPAN</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-max">
          <thead class="bg-gray-50 text-left text-sm text-gray-600">
            <tr>
              <th class="p-4">ID Penitipan</th>
              <th class="p-4">Pemilik</th>
              <th class="p-4">Hewan</th>
              <th class="p-4">Tanggal Masuk</th>
              <th class="p-4">Tanggal Keluar</th>
              <th class="p-4">Status</th>
              <th class="p-4">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-4">#PT-001</td>
              <td class="p-4 font-medium">Budi Santoso</td>
              <td class="p-4">Buddy (Anjing)</td>
              <td class="p-4">25 Sep 2025</td>
              <td class="p-4">28 Sep 2025</td>
              <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
              <td class="p-4"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-4">#PT-002</td>
              <td class="p-4 font-medium">Citra Lestari</td>
              <td class="p-4">Milo (Kucing)</td>
              <td class="p-4">26 Sep 2025</td>
              <td class="p-4">27 Sep 2025</td>
              <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span></td>
              <td class="p-4"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-4">#PT-003</td>
              <td class="p-4 font-medium">Doni Setiawan</td>
              <td class="p-4">Leo (Anjing)</td>
              <td class="p-4">20 Sep 2025</td>
              <td class="p-4">23 Sep 2025</td>
              <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Selesai</span></td>
              <td class="p-4"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
            </tr>
             <tr class="border-b hover:bg-gray-50">
              <td class="p-4">#PT-004</td>
              <td class="p-4 font-medium">Eka Putri</td>
              <td class="p-4">Coco (Kucing)</td>
              <td class="p-4">22 Sep 2025</td>
              <td class="p-4">24 Sep 2025</td>
              <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Dibatalkan</span></td>
              <td class="p-4"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
