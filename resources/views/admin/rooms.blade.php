@extends('admin.layouts.app')

@section('content')
<div class="flex flex-col h-full">
  <!-- Header -->
  <header class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-3xl font-bold">UPDATE KONDISI</h1>
      <p class="text-gray-600">Kelola update kondisi hewan dalam penitipan</p>
    </div>
    <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      <span>Tambah Update</span>
    </button>
  </header>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Update Hari Ini</h3>
        <p class="text-3xl font-bold mt-2">15</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Kondisi Sehat</h3>
        <p class="text-3xl font-bold mt-2">12</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Perlu Perhatian</h3>
        <p class="text-3xl font-bold mt-2">3</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Staff Aktif</h3>
        <p class="text-3xl font-bold mt-2">7</p>
    </div>
  </div>

  <!-- Filters -->
  <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex flex-wrap items-center gap-4">
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Penitipan</option>
        <option>Aktif</option>
        <option>Selesai</option>
      </select>
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Staff</option>
        <option>Staff A</option>
        <option>Staff B</option>
      </select>
      <input type="date" class="px-4 py-2 border rounded-lg">
    </div>
  </div>

  <!-- Updates Table -->
  <div class="flex-1 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="font-semibold">DAFTAR UPDATE KONDISI</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-max">
        <thead class="bg-gray-50 text-left text-sm text-gray-600">
          <tr>
            <th class="p-4">ID Update</th>
            <th class="p-4">Penitipan</th>
            <th class="p-4">Hewan</th>
            <th class="p-4">Staff</th>
            <th class="p-4">Kondisi Hewan</th>
            <th class="p-4">Aktivitas</th>
            <th class="p-4">Waktu Update</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-mono text-sm">UK-001</td>
            <td class="p-4 font-mono text-sm">PT-001</td>
            <td class="p-4 font-medium">Buddy (Anjing)</td>
            <td class="p-4">Staff A</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sehat</span></td>
            <td class="p-4 text-sm">Makan normal, bermain aktif</td>
            <td class="p-4">28 Sep 2025 14:30</td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-mono text-sm">UK-002</td>
            <td class="p-4 font-mono text-sm">PT-002</td>
            <td class="p-4 font-medium">Milo (Kucing)</td>
            <td class="p-4">Staff B</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Perlu Perhatian</span></td>
            <td class="p-4 text-sm">Kurang nafsu makan</td>
            <td class="p-4">28 Sep 2025 15:45</td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-mono text-sm">UK-003</td>
            <td class="p-4 font-mono text-sm">PT-003</td>
            <td class="p-4 font-medium">Leo (Anjing)</td>
            <td class="p-4">Staff A</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sehat</span></td>
            <td class="p-4 text-sm">Tidur nyenyak, kondisi baik</td>
            <td class="p-4">28 Sep 2025 16:20</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection