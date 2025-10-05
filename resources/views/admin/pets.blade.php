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
      <p class="text-3xl font-bold mt-2">0</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Anjing</h3>
      <p class="text-3xl font-bold mt-2">0</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Kucing</h3>
      <p class="text-3xl font-bold mt-2">0</p>
    </div>
  </div>

  <!-- Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4">
      <input type="text" id="petSearch"
        placeholder="Cari nama hewan, pemilik, atau ID"
        class="flex-grow w-full sm:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        onkeyup="searchFunction()">
      <select id="jenisFilter" class="px-4 py-2 border rounded-lg" onchange="searchFunction()">
        <option value="">Semua Jenis</option>
        <option value="anjing">Anjing</option>
        <option value="kucing">Kucing</option>
      </select>
      <select id="statusFilter" class="px-4 py-2 border rounded-lg" onchange="searchFunction()">
        <option value="">Semua Status</option>
        <option value="dalam_penitipan">Dalam Penitipan</option>
        <option value="di_rumah">Di Rumah</option>
      </select>
    </div>
  </div>

  <!-- Pets Table (card) -->
  <div class="flex-1 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="font-semibold">DAFTAR HEWAN PELIHARAAN</h3>
    </div>

    <!-- SCROLL AREA HANYA UNTUK DAFTAR -->
    <div class="relative">
      <!-- atur tinggi area list di sini -->
      <div class="max-h-[400px] overflow-y-scroll overflow-x-auto scrollbar-custom">
        <table class="w-full min-w-max">
          <thead class="bg-gray-50 text-left text-sm text-gray-600 sticky top-0 z-10">
            <tr>
              <th class="p-4">Hewan</th>
              <th class="p-4">Pemilik</th>
              <th class="p-4">Detail Fisik</th>
              <th class="p-4">Kondisi Khusus</th>
              <th class="p-4">Riwayat Penitipan</th>
              <th class="p-4">Aksi</th>
            </tr>
          </thead>
          <tbody id="tableBody" class="text-sm">
            <!-- Data hewan akan muncul di sini -->
          </tbody>
        </table>

        <div id="noResults" class="p-4 text-center text-gray-500 hidden">
          Tidak ada hasil ditemukan
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Custom scrollbar styling */
.scrollbar-custom::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

.scrollbar-custom::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 5px;
}

.scrollbar-custom::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 5px;
}

.scrollbar-custom::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* For Firefox */
.scrollbar-custom {
  scrollbar-width: thin;
  scrollbar-color: #888 #f1f1f1;
}
</style>

<script>
// Fungsi filter sederhana
function searchFunction() {
  var searchValue = document.getElementById('petSearch').value.toLowerCase();
  var jenisValue  = document.getElementById('jenisFilter').value.toLowerCase();
  var statusValue = document.getElementById('statusFilter').value.toLowerCase();

  var rows = document.getElementsByClassName('pet-row');
  var visibleCount = 0;

  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    var rowText   = row.innerText.toLowerCase();
    var rowJenis  = (row.getAttribute('data-jenis') || '').toLowerCase();
    var rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

    var showRow = true;

    if (searchValue && !rowText.includes(searchValue)) showRow = false;
    if (jenisValue  && rowJenis !== jenisValue)       showRow = false;
    if (statusValue && rowStatus.replace(' ', '_') !== statusValue) showRow = false;

    row.style.display = showRow ? '' : 'none';
    if (showRow) visibleCount++;
  }

  var noResults = document.getElementById('noResults');
  if (noResults) noResults.classList.toggle('hidden', visibleCount !== 0);
}

console.log('Pets page script loaded.');
</script>
@endsection