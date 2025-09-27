@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <header class="mb-2">
    <h1 class="text-3xl font-bold">Manajemen Penitipan</h1>
    <p class="text-gray-500">Kelola semua penitipan dan reservasi hewan</p>
  </header>

  <!-- Statistik Ringkas -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="text-sm text-gray-500">Total Penitipan</h4>
      <p class="text-2xl font-bold text-gray-800">128</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="text-sm text-gray-500">Aktif</h4>
      <p class="text-2xl font-bold text-green-600">34</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="text-sm text-gray-500">Pending</h4>
      <p class="text-2xl font-bold text-yellow-500">12</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="text-sm text-gray-500">Selesai</h4>
      <p class="text-2xl font-bold text-blue-500">82</p>
    </div>
  </div>

  <!-- Filters & Actions -->
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div class="flex bg-gray-200 rounded-lg p-1">
      <button id="btnTable" class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white rounded-md shadow">Table View</button>
      <button id="btnCalendar" class="px-4 py-2 text-sm font-semibold text-gray-500">Calendar View</button>
    </div>

    <div class="flex flex-wrap items-center gap-3">
      <input id="searchInput" type="text" placeholder="Cari nama pemilik / ID / hewan"
             class="w-full sm:w-64 px-4 py-2 border rounded-lg" />

      <select id="statusFilter" class="px-4 py-2 border rounded-lg">
        <option value="">Semua Status</option>
        <option value="Aktif">Aktif</option>
        <option value="Pending">Pending</option>
        <option value="Selesai">Selesai</option>
        <option value="Dibatalkan">Dibatalkan</option>
      </select>

      <input id="dateFilter" type="date" class="px-4 py-2 border rounded-lg" />

      <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Penitipan</button>
    </div>
  </div>

  <!-- Notifikasi contoh -->
  <div id="alertSuccess" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
    <span class="text-sm">✅ Penitipan baru berhasil ditambahkan!</span>
    <button class="text-sm font-medium hover:underline">Lihat Detail</button>
  </div>

  <!-- Tabel Penitipan -->
  <div id="tableView" class="flex-1 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between">
      <h3 class="font-semibold">DAFTAR PENITIPAN</h3>
      <button class="text-sm text-blue-600 hover:underline">Export CSV</button>
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

        <tbody id="bookingTable">
          <tr data-status="Aktif">
            <td class="p-4">#PT-001</td>
            <td class="p-4 font-medium">Budi Santoso</td>
            <td class="p-4">Buddy (Anjing)</td>
            <td class="p-4">25 Sep 2025</td>
            <td class="p-4">28 Sep 2025</td>
            <td class="p-4">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-green-700 bg-green-100">Aktif</span>
            </td>
            <td class="p-4 space-x-3">
              <a href="#" class="text-blue-600 hover:underline">Detail</a>
              <a href="#" class="text-yellow-600 hover:underline">Edit</a>
            </td>
          </tr>

          <tr data-status="Pending">
            <td class="p-4">#PT-002</td>
            <td class="p-4 font-medium">Citra Lestari</td>
            <td class="p-4">Milo (Kucing)</td>
            <td class="p-4">26 Sep 2025</td>
            <td class="p-4">27 Sep 2025</td>
            <td class="p-4">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-yellow-700 bg-yellow-100">Pending</span>
            </td>
            <td class="p-4 space-x-3">
              <a href="#" class="text-blue-600 hover:underline">Detail</a>
              <a href="#" class="text-yellow-600 hover:underline">Edit</a>
            </td>
          </tr>

          <tr data-status="Selesai">
            <td class="p-4">#PT-003</td>
            <td class="p-4 font-medium">Doni Setiawan</td>
            <td class="p-4">Leo (Anjing)</td>
            <td class="p-4">20 Sep 2025</td>
            <td class="p-4">23 Sep 2025</td>
            <td class="p-4">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-gray-700 bg-gray-100">Selesai</span>
            </td>
            <td class="p-4 space-x-3">
              <a href="#" class="text-blue-600 hover:underline">Detail</a>
              <a href="#" class="text-yellow-600 hover:underline">Edit</a>
            </td>
          </tr>

          <tr data-status="Dibatalkan">
            <td class="p-4">#PT-004</td>
            <td class="p-4 font-medium">Eka Putri</td>
            <td class="p-4">Coco (Kucing)</td>
            <td class="p-4">22 Sep 2025</td>
            <td class="p-4">24 Sep 2025</td>
            <td class="p-4">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-red-700 bg-red-100">Dibatalkan</span>
            </td>
            <td class="p-4 space-x-3">
              <a href="#" class="text-blue-600 hover:underline">Detail</a>
              <a href="#" class="text-yellow-600 hover:underline">Edit</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Calendar View -->
  <div id="calendarView" class="hidden bg-white rounded-lg shadow-md p-6">
    <h3 class="font-semibold mb-4">Calendar View</h3>
    <div class="grid grid-cols-7 gap-4 text-center">
      <div>
        <p class="font-medium">Sen (22)</p>
        <div class="bg-gray-100 rounded-lg p-2 mt-2">Coco</div>
      </div>
      <div>
        <p class="font-medium">Rab (23)</p>
        <div class="bg-gray-100 rounded-lg p-2 mt-2">Leo</div>
      </div>
      <div>
        <p class="font-medium">Kam (24)</p>
        <div class="bg-gray-100 rounded-lg p-2 mt-2">Coco</div>
      </div>
      <div>
        <p class="font-medium">Jum (25)</p>
        <div class="bg-gray-100 rounded-lg p-2 mt-2">Buddy</div>
      </div>
      <div>
        <p class="font-medium">Sab (26)</p>
        <div class="bg-gray-100 rounded-lg p-2 mt-2">Milo</div>
      </div>
      <div>
        <p class="font-medium">Min (27)</p>
        <div class="bg-gray-100 rounded-lg p-2 mt-2">Milo</div>
      </div>
      <div>
        <p class="font-medium">Sen (28)</p>
        <div class="bg-gray-100 rounded-lg p-2 mt-2">Buddy</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
(function () {
  'use strict';

  const searchInput = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  const dateFilter = document.getElementById('dateFilter');
  const rowsNodeList = document.querySelectorAll('#bookingTable tr');

  const rows = Array.prototype.slice.call(rowsNodeList);

  function normalize(text) {
    return (text || '').toString().toLowerCase().trim();
  }

  function filterTable() {
    const q = normalize(searchInput.value);
    const status = normalize(statusFilter.value);
    const date = dateFilter.value;

    rows.forEach(row => {
      const id = normalize(row.cells[0]?.innerText);      // ID Penitipan
      const owner = normalize(row.cells[1]?.innerText);   // Pemilik
      const pet = normalize(row.cells[2]?.innerText);     // Hewan
      const masuk = normalize(row.cells[3]?.innerText);   // Tgl masuk
      const keluar = normalize(row.cells[4]?.innerText);  // Tgl keluar
      const rowStatus = normalize(row.dataset.status || '');

      const searchable = `${id} ${owner} ${pet} ${masuk} ${keluar}`;

      const matchesSearch = q === '' || searchable.includes(q);
      const matchesStatus = status === '' || rowStatus === status;
      let matchesDate = true;
      if (date) {
        matchesDate = masuk.includes(date) || keluar.includes(date);
      }

      row.style.display = (matchesSearch && matchesStatus && matchesDate) ? '' : 'none';
    });
  }

  if (searchInput) searchInput.addEventListener('input', filterTable);
  if (statusFilter) statusFilter.addEventListener('change', filterTable);
  if (dateFilter) dateFilter.addEventListener('change', filterTable);

  filterTable();
  // --- SWITCH VIEW ---
  const btnTable = document.getElementById('btnTable');
  const btnCalendar = document.getElementById('btnCalendar');
  const tableView = document.getElementById('tableView');
  const calendarView = document.getElementById('calendarView');

  btnTable.addEventListener('click', () => {
    tableView.classList.remove('hidden');
    calendarView.classList.add('hidden');
    btnTable.classList.add('bg-white', 'text-gray-800', 'shadow');
    btnCalendar.classList.remove('bg-white', 'text-gray-800', 'shadow');
    btnCalendar.classList.add('text-gray-500');
  });

  btnCalendar.addEventListener('click', () => {
    calendarView.classList.remove('hidden');
    tableView.classList.add('hidden');
    btnCalendar.classList.add('bg-white', 'text-gray-800', 'shadow');
    btnTable.classList.remove('bg-white', 'text-gray-800', 'shadow');
    btnTable.classList.add('text-gray-500');
  });
})();
@endpush
