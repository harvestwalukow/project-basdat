@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <header class="mb-2 flex justify-between items-end">
    <div>
      <h1 class="text-3xl font-bold">Manajemen Penitipan</h1>
      <p class="text-gray-500">Kelola semua penitipan dan reservasi hewan</p>
    </div>
    <span class="text-xs text-black bg-gray-50 px-2 py-1 rounded border">Sumber: FactTransaksi</span>
  </header>

  <!-- Statistik Ringkas -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7 gap-4">
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
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl shadow border border-purple-200">
      <h4 class="text-sm text-purple-700 font-medium">Premium Kucing</h4>
      <p class="text-2xl font-bold text-purple-800">{{ $premiumKucingUsed ?? 0 }}/25</p>
      <p class="text-xs text-purple-600 mt-1">{{ 25 - ($premiumKucingUsed ?? 0) }} tersedia</p>
    </div>
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl shadow border border-orange-200">
      <h4 class="text-sm text-orange-700 font-medium">Basic<br>Kucing</h4>
      <p class="text-2xl font-bold text-orange-800">{{ $basicKucingUsed ?? 0 }}/25</p>
      <p class="text-xs text-orange-600 mt-1">{{ 25 - ($basicKucingUsed ?? 0) }} tersedia</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-xl shadow border border-indigo-200">
      <h4 class="text-sm text-indigo-700 font-medium">Premium Anjing</h4>
      <p class="text-2xl font-bold text-indigo-800">{{ $premiumAnjingUsed ?? 0 }}/25</p>
      <p class="text-xs text-indigo-600 mt-1">{{ 25 - ($premiumAnjingUsed ?? 0) }} tersedia</p>
    </div>
    <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-xl shadow border border-teal-200">
      <h4 class="text-sm text-teal-700 font-medium">Basic<br>Anjing</h4>
      <p class="text-2xl font-bold text-teal-800">{{ $basicAnjingUsed ?? 0 }}/25</p>
      <p class="text-xs text-teal-600 mt-1">{{ 25 - ($basicAnjingUsed ?? 0) }} tersedia</p>
    </div>
  </div>

  <!-- Filters & Actions -->
  <div class="flex flex-wrap items-center gap-3">
    <input id="searchInput" type="text" placeholder="Cari nama pemilik / ID / hewan"
           class="w-full sm:w-64 px-4 py-2 border rounded-lg" />

    <select id="statusFilter" class="px-4 py-2 border rounded-lg">
      <option value="">Semua Status</option>
      <option value="Aktif">Aktif</option>
      <option value="Selesai">Selesai</option>
      <option value="Dibatalkan">Dibatalkan</option>
    </select>

    <select id="sortFilter" class="px-4 py-2 border rounded-lg">
      <option value="">Urutkan</option>
      <option value="masuk-desc">Tanggal Masuk (Terbaru)</option>
      <option value="masuk-asc">Tanggal Masuk (Terlama)</option>
      <option value="keluar-desc">Tanggal Keluar (Terbaru)</option>
      <option value="keluar-asc">Tanggal Keluar (Terlama)</option>
    </select>
  </div>

  <!-- Notifikasi contoh (dihapus) -->
  {{-- <div id="alertSuccess" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
    <span class="text-sm">✅ Penitipan baru berhasil ditambahkan!</span>
    <button class="text-sm font-medium hover:underline">Lihat Detail</button>
  </div> --}}

  <!-- Tabel Penitipan -->
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
          </tr>
        </thead>

        <tbody id="bookingTable">
          @forelse($penitipans as $penitipan)
            <tr class="border-b hover:bg-gray-50" 
                data-status="{{ strtolower($penitipan->status) }}"
                data-masuk="{{ $penitipan->tanggal_masuk }}"
                data-keluar="{{ $penitipan->tanggal_keluar }}">
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
            </tr>
          @empty
            <tr>
              <td colspan="6" class="p-8 text-center text-gray-500">
                <p>Belum ada data penitipan</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  'use strict';

  const searchInput  = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  const sortFilter   = document.getElementById('sortFilter');
  const tableBody    = document.getElementById('bookingTable');
  let rows           = Array.from(document.querySelectorAll('#bookingTable tr'));

  function normalize(text) {
    return (text || '').toString().toLowerCase().trim();
  }

  function filterTable() {
    const q      = normalize(searchInput.value);
    const status = normalize(statusFilter.value);

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

      row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
  }

  function sortTable() {
    const sortValue = sortFilter.value;
    if (!sortValue) return;

    const [field, order] = sortValue.split('-');
    
    // Get visible rows only
    const visibleRows = rows.filter(row => row.style.display !== 'none');
    
    // Sort the rows
    visibleRows.sort((a, b) => {
      const dateA = new Date(a.dataset[field]);
      const dateB = new Date(b.dataset[field]);
      
      if (order === 'asc') {
        return dateA - dateB;
      } else {
        return dateB - dateA;
      }
    });

    // Re-append rows to table in sorted order
    visibleRows.forEach(row => {
      tableBody.appendChild(row);
    });
    
    // Update rows array to match new order
    rows = Array.from(document.querySelectorAll('#bookingTable tr'));
  }

  if (searchInput)  searchInput.addEventListener('input',  filterTable);
  if (statusFilter) statusFilter.addEventListener('change', filterTable);
  if (sortFilter)   sortFilter.addEventListener('change', sortTable);

  filterTable();
})();
</script>
@endpush