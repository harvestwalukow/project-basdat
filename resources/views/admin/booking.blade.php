@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <header class="mb-2">
    <h1 class="text-3xl font-bold">Manajemen Penitipan</h1>
    <p class="text-gray-500">Kelola semua penitipan dan reservasi hewan</p>
  </header>

  <!-- Statistik Ringkas (Pending dihapus) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="text-sm text-gray-500">Total Penitipan</h4>
      <p class="text-2xl font-bold text-gray-800">{{ $totalPenitipan }}</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="text-sm text-gray-500">Aktif</h4>
      <p class="text-2xl font-bold text-green-600">{{ $aktifCount }}</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="text-sm text-gray-500">Selesai</h4>
      <p class="text-2xl font-bold text-blue-500">{{ $selesaiCount }}</p>
    </div>
  </div>

  <!-- Filters & Actions (hapus tombol Tambah Penitipan) -->
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div class="flex bg-gray-200 rounded-lg p-1">
      <button id="btnTable" class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white rounded-md shadow">Table View</button>
      <button id="btnCalendar" class="px-4 py-2 text-sm font-semibold text-gray-500">Calendar View</button>
    </div>

    <div class="flex flex-wrap items-center gap-3">
      <input id="searchInput" type="text" placeholder="Cari nama pemilik / ID / hewan"
             class="w-full sm:w-64 px-4 py-2 border rounded-lg" />

      <!-- Pending dihapus dari opsi -->
      <select id="statusFilter" class="px-4 py-2 border rounded-lg">
        <option value="">Semua Status</option>
        <option value="Aktif">Aktif</option>
        <option value="Selesai">Selesai</option>
        <option value="Dibatalkan">Dibatalkan</option>
      </select>

      <input id="dateFilter" type="date" class="px-4 py-2 border rounded-lg" />
    </div>
  </div>

  <!-- Notifikasi contoh (dihapus) -->
  {{-- <div id="alertSuccess" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
    <span class="text-sm">✅ Penitipan baru berhasil ditambahkan!</span>
    <button class="text-sm font-medium hover:underline">Lihat Detail</button>
  </div> --}}

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
          @forelse($penitipans as $penitipan)
            <tr class="border-b hover:bg-gray-50" data-status="{{ strtolower($penitipan->status) }}">
              <td class="p-4">PNT-{{ str_pad($penitipan->id_penitipan, 4, '0', STR_PAD_LEFT) }}</td>
              <td class="p-4">{{ $penitipan->pemilik->nama_lengkap }}</td>
              <td class="p-4">{{ $penitipan->hewan->nama_hewan }}</td>
              <td class="p-4">{{ \Carbon\Carbon::parse($penitipan->tanggal_masuk)->format('d M Y') }}</td>
              <td class="p-4">{{ \Carbon\Carbon::parse($penitipan->tanggal_keluar)->format('d M Y') }}</td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                  @if($penitipan->status == 'aktif') bg-green-100 text-green-700
                  @elseif($penitipan->status == 'pending') bg-yellow-100 text-yellow-700
                  @elseif($penitipan->status == 'selesai') bg-blue-100 text-blue-700
                  @else bg-red-100 text-red-700
                  @endif">
                  {{ ucfirst($penitipan->status) }}
                </span>
              </td>
              <td class="p-4">
                <div class="flex gap-2">
                  <button class="text-blue-600 hover:underline text-sm">Detail</button>
                  <button class="text-green-600 hover:underline text-sm">Edit</button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-gray-500">
                <p>Belum ada data penitipan</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Calendar View -->
<div id="calendarView" class="hidden bg-white rounded-lg shadow-md p-6">
  <h3 class="font-semibold mb-4">Calendar View</h3>
  <div class="grid grid-cols-7 gap-4 text-center">
    <!-- Data calendar akan muncul di sini -->
  </div>
</div>
@endsection

@push('scripts')
(function () {
  'use strict';

  const searchInput  = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  const dateFilter   = document.getElementById('dateFilter');
  const rows         = Array.from(document.querySelectorAll('#bookingTable tr'));

  function normalize(text) {
    return (text || '').toString().toLowerCase().trim();
  }

  function filterTable() {
    const q      = normalize(searchInput.value);
    const status = normalize(statusFilter.value);
    const date   = dateFilter.value; // format YYYY-MM-DD (catatan: contoh data tanggal human-readable)

    rows.forEach(row => {
      const id      = normalize(row.cells[0]?.innerText);
      const owner   = normalize(row.cells[1]?.innerText);
      const pet     = normalize(row.cells[2]?.innerText);
      const masuk   = normalize(row.cells[3]?.innerText);
      const keluar  = normalize(row.cells[4]?.innerText);
      const rStatus = normalize(row.dataset.status || '');

      const searchable   = `${id} ${owner} ${pet} ${masuk} ${keluar}`;
      const matchesSearch = q === '' || searchable.includes(q);
      const matchesStatus = status === '' || rStatus === status;

      // NB: jika ingin filter tanggal benar2 presisi, simpan tanggal sebagai data-attr ISO.
      let matchesDate = true;
      if (date) {
        matchesDate = masuk.includes(date) || keluar.includes(date);
      }

      row.style.display = (matchesSearch && matchesStatus && matchesDate) ? '' : 'none';
    });
  }

  if (searchInput)  searchInput.addEventListener('input',  filterTable);
  if (statusFilter) statusFilter.addEventListener('change', filterTable);
  if (dateFilter)   dateFilter.addEventListener('change',  filterTable);

  filterTable();

  // --- SWITCH VIEW ---
  const btnTable     = document.getElementById('btnTable');
  const btnCalendar  = document.getElementById('btnCalendar');
  const tableView    = document.getElementById('tableView');
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