@extends('admin.layouts.app')

@section('content')
<div class="flex flex-col h-full">
  <!-- Header -->
  <header class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-3xl font-bold">HEWAN</h1>
      <p class="text-gray-600">Daftar semua hewan yang terdaftar di sistem</p>
    </div>
    <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
      <span>Tambah Hewan</span>
    </button>
  </header>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-semibold text-gray-600">Total Hewan</h3>
          <p class="text-3xl font-bold mt-2">26</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-semibold text-gray-600">Anjing</h3>
          <p class="text-3xl font-bold mt-2">18</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-semibold text-gray-600">Kucing</h3>
          <p class="text-3xl font-bold mt-2">8</p>
      </div>
  </div>

  <!-- Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4">
      <input type="text" placeholder="Cari nama hewan, pemilik, atau ID" class="flex-grow w-full sm:w-auto px-4 py-2 border rounded-lg">
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Jenis</option>
        <option>Anjing</option>
        <option>Kucing</option>
      </select>
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Status</option>
        <option>Dalam Penitipan</option>
        <option>Di Rumah</option>
      </select>
    </div>
  </div>

  <!-- Pets Table -->
  <div class="flex-1 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="font-semibold">DAFTAR HEWAN PELIHARAAN</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-max">
        <thead class="bg-gray-50 text-left text-sm text-gray-600">
          <tr>
            <th class="p-4">Hewan</th>
            <th class="p-4">Pemilik</th>
            <th class="p-4">Detail Fisik</th>
            <th class="p-4">Kondisi Khusus</th>
            <th class="p-4">Riwayat Penitipan</th>
            <th class="p-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-xl">🐶</div>
                <div>
                  <p class="font-medium">Buddy</p>
                  <p class="text-sm text-gray-500">Golden Retriever</p>
                </div>
              </div>
            </td>
            <td class="p-4 font-medium">Siti Indah</td>
            <td class="p-4 text-sm">Jantan, 2 thn, 25kg</td>
            <td class="p-4 text-sm">15 Jan 2025</td>
            <td class="p-4 text-sm">5 kali</td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Lihat</button>
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Edit</button>
              </div>
            </td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-xl">🐱</div>
                <div>
                  <p class="font-medium">Milo</p>
                  <p class="text-sm text-gray-500">Persian</p>
                </div>
              </div>
            </td>
            <td class="p-4 font-medium">Fajar Hidayat</td>
            <td class="p-4 text-sm">Betina, 3 thn, 4kg</td>
            <td class="p-4 text-sm">20 Mar 2025</td>
            <td class="p-4 text-sm">2 kali</td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Lihat</button>
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Edit</button>
              </div>
            </td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-xl">🐶</div>
                <div>
                  <p class="font-medium">Leo</p>
                  <p class="text-sm text-gray-500">Beagle</p>
                </div>
              </div>
            </td>
            <td class="p-4 font-medium">Heru Wasesa</td>
            <td class="p-4 text-sm">Jantan, 1 thn, 12kg</td>
            <td class="p-4 text-sm">05 Feb 2025</td>
            <td class="p-4 text-sm">8 kali</td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Lihat</button>
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Edit</button>
              </div>
            </td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-xl">🐱</div>
                <div>
                  <p class="font-medium">Coco</p>
                  <p class="text-sm text-gray-500">Siberian</p>
                </div>
              </div>
            </td>
            <td class="p-4 font-medium">Mega Lestari</td>
            <td class="p-4 text-sm">Betina, 5 thn, 5kg</td>
            <td class="p-4 text-sm">11 Nov 2024</td>
            <td class="p-4 text-sm">12 kali</td>
            <td class="p-4">
               <div class="flex items-center gap-2">
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Lihat</button>
                <button class="p-2 bg-gray-200 rounded-md hover:bg-gray-300">Edit</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
