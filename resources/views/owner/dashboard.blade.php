@extends('owner.layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Main Content -->
  <div class="lg:col-span-2">
    <!-- Header -->
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold">OWNER</h1>
      <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span>Tambah Karyawan</span>
      </button>
    </header>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <h3 class="text-lg font-semibold text-gray-600">Total Karyawan</h3>
        <p class="text-3xl font-bold mt-2">12</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <h3 class="text-lg font-semibold text-gray-600">Karyawan Aktif</h3>
        <p class="text-3xl font-bold mt-2">9</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <h3 class="text-lg font-semibold text-gray-600">Sedang Cuti</h3>
        <p class="text-3xl font-bold mt-2">1</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow-md text-center">
        <h3 class="text-lg font-semibold text-gray-600">Full Time</h3>
        <p class="text-3xl font-bold mt-2">3</p>
        <p class="text-sm text-gray-500">Karyawan Tetap</p>
      </div>
    </div>

    <!-- Employee List -->
    <div>
      <h2 class="text-2xl font-bold mb-4">DAFTAR KARYAWAN</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Employee Card -->
        <div class="bg-white p-4 rounded-lg shadow-md">
          <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center font-bold text-xl">SI</div>
              <div>
                <p class="font-bold">Siti Indah</p>
                <p class="text-sm text-gray-500">siti@mail.com</p>
                <p class="text-sm text-gray-500">081234567890</p>
              </div>
            </div>
            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
          </div>
          <div class="mt-4 text-sm">
            <p><span class="font-semibold">Status:</span> Full Time</p>
            <p><span class="font-semibold">Jadwal Kerja:</span> 
              <span class="px-2 py-1 text-xs bg-gray-200 rounded-full">Selasa</span> 
              <span class="px-2 py-1 text-xs bg-gray-200 rounded-full">Senin</span>
            </p>
            <p class="mt-2"><span class="font-semibold">Tugas:</span> Grooming, Penjagaan</p>
          </div>
          <div class="mt-4 border-t pt-4 text-sm text-gray-600 flex justify-between items-center">
             <div>
                <p><span class="font-semibold">Bergabung:</span> 12 Jan 2024</p>
                <p><span class="font-semibold">Gaji:</span> Rp 5.000.000</p>
             </div>
             <div class="flex items-center gap-2">
                <button class="text-blue-600 hover:underline">Detail</button>
                <button class="p-2 bg-gray-100 rounded-md hover:bg-gray-200">Edit</button>
             </div>
          </div>
        </div>
        <!-- More cards -->
        <div class="bg-white p-4 rounded-lg shadow-md flex items-center justify-center text-gray-400">
            <p>Data Karyawan Lain</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md flex items-center justify-center text-gray-400">
            <p>Data Karyawan Lain</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Sidebar -->
  <div class="space-y-8">
    <!-- Today's Tasks -->
     <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">TUGAS HARI INI</h3>
       <div class="space-y-4">
        <div class="p-3 bg-gray-50 border rounded-lg">
            <div class="flex items-center justify-between">
                <p class="font-semibold">09:00 - Ahmad Rizki</p>
                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Selesai</span>
            </div>
            <p class="text-sm mt-1">Grooming Princess (Persian Cat)</p>
         </div>
        <div class="p-3 bg-gray-50 border rounded-lg">
            <p class="font-semibold">11:00 - Siti Indah</p>
            <p class="text-sm mt-1">Check-in Buddy (Golden Retriever)</p>
         </div>
       </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">AKSI CEPAT</h3>
        <div class="flex flex-col space-y-3">
            <button class="w-full px-4 py-2 text-left bg-gray-100 rounded-lg hover:bg-gray-200">Assign Tugas Baru</button>
            <button class="w-full px-4 py-2 text-left bg-gray-100 rounded-lg hover:bg-gray-200">Edit Jadwal Kerja</button>
            <button class="w-full px-4 py-2 text-left bg-gray-100 rounded-lg hover:bg-gray-200">Lihat Absensi Karyawan</button>
        </div>
     </div>
  </div>
</div>
@endsection
