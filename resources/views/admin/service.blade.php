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
      <p class="text-3xl font-bold mt-2" id="totalPaket">7</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Paket Aktif</h3>
      <p class="text-3xl font-bold mt-2" id="paketAktif">6</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Total Pemesanan</h3>
      <p class="text-3xl font-bold mt-2">45</p>
    </div>
  </div>

  <!-- Search & Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
      <div class="flex flex-wrap items-center gap-4">
        <input
          type="text"
          id="serviceSearch"
          placeholder="Cari nama paket atau deskripsi"
          class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
          onkeyup="filterServices()"
        >
        <select id="tipeFilter" class="px-4 py-2 border rounded-lg" onchange="filterServices()">
          <option value="">Semua Tipe</option>
          <option value="paket">Paket</option>
          <option value="tambahan">Tambahan</option>
        </select>
        <select id="statusFilter" class="px-4 py-2 border rounded-lg" onchange="filterServices()">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="non_aktif">Non-Aktif</option>
        </select>
        <select id="hargaFilter" class="px-4 py-2 border rounded-lg" onchange="filterServices()">
          <option value="">Semua Harga</option>
          <option value="murah">&lt; Rp 200.000</option>
          <option value="sedang">Rp 200.000 - Rp 300.000</option>
          <option value="mahal">&gt; Rp 300.000</option>
        </select>
      </div>
    </div>
    <div id="searchStatus" class="text-sm text-gray-600" style="display:none;"></div>
  </div>

  <!-- Table Card -->
  <div class="flex-1 bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b">
      <h3 class="font-semibold">DAFTAR PAKET LAYANAN</h3>
    </div>

    <!-- TABLE (hidden native scrollbar) -->
    <div id="tableScroll" class="overflow-x-auto hide-scrollbar">
      <table class="w-full min-w-[1200px]">
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
        <tbody id="tableBody">
          {{-- PAKET --}}
          <tr class="service-row border-b hover:bg-gray-50" data-tipe="paket" data-status="aktif" data-harga="150000">
            <td class="p-4 font-medium">Paket Basic</td>
            <td class="p-4">Penitipan standar dengan fasilitas dasar</td>
            <td class="p-4">Rp 150.000</td>
            <td class="p-4 text-center">25</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><div class="flex items-center gap-2"><a href="#" class="text-blue-600 hover:underline text-sm">Edit</a><a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a></div></td>
          </tr>

          <tr class="service-row border-b hover:bg-gray-50" data-tipe="paket" data-status="aktif" data-harga="250000">
            <td class="p-4 font-medium">Paket Premium</td>
            <td class="p-4">Penitipan premium dengan fasilitas lengkap dan grooming</td>
            <td class="p-4">Rp 250.000</td>
            <td class="p-4 text-center">15</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><div class="flex items-center gap-2"><a href="#" class="text-blue-600 hover:underline text-sm">Edit</a><a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a></div></td>
          </tr>

          <tr class="service-row border-b hover:bg-gray-50" data-tipe="paket" data-status="non_aktif" data-harga="350000">
            <td class="p-4 font-medium">Paket Deluxe</td>
            <td class="p-4">Penitipan mewah dengan layanan eksklusif dan spa</td>
            <td class="p-4">Rp 350.000</td>
            <td class="p-4 text-center">5</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Non-Aktif</span></td>
            <td class="p-4"><div class="flex items-center gap-2"><a href="#" class="text-blue-600 hover:underline text-sm">Edit</a><a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a></div></td>
          </tr>

          {{-- TAMBAHAN --}}
          <tr class="service-row border-b hover:bg-gray-50" data-tipe="tambahan" data-status="aktif" data-harga="150000">
            <td class="p-4 font-medium">Grooming Premium</td>
            <td class="p-4">Spa lengkap, potong kuku, bersih telinga, aromaterapi</td>
            <td class="p-4">Rp 150.000</td>
            <td class="p-4 text-center">30</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><div class="flex items-center gap-2"><a href="#" class="text-blue-600 hover:underline text-sm">Edit</a><a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a></div></td>
          </tr>

          <tr class="service-row border-b hover:bg-gray-50" data-tipe="tambahan" data-status="aktif" data-harga="100000">
            <td class="p-4 font-medium">Kolam Renang</td>
            <td class="p-4">Layanan berenang bagi anabul</td>
            <td class="p-4">Rp 100.000</td>
            <td class="p-4 text-center">22</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><div class="flex items-center gap-2"><a href="#" class="text-blue-600 hover:underline text-sm">Edit</a><a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a></div></td>
          </tr>

          <tr class="service-row border-b hover:bg-gray-50" data-tipe="tambahan" data-status="aktif" data-harga="100000">
            <td class="p-4 font-medium">Pick-up & Delivery</td>
            <td class="p-4">Layanan antar jemput dalam radius 10km</td>
            <td class="p-4">Rp 100.000</td>
            <td class="p-4 text-center">18</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><div class="flex items-center gap-2"><a href="#" class="text-blue-600 hover:underline text-sm">Edit</a><a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a></div></td>
          </tr>

          <tr class="service-row hover:bg-gray-50" data-tipe="tambahan" data-status="aktif" data-harga="45000">
            <td class="p-4 font-medium">Enrichment Extra</td>
            <td class="p-4">Sesi stimulasi 15–20 menit (puzzle feeder, lick mat, sniffing)</td>
            <td class="p-4">Rp 45.000</td>
            <td class="p-4 text-center">12</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4"><div class="flex items-center gap-2"><a href="#" class="text-blue-600 hover:underline text-sm">Edit</a><a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a></div></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- BOTTOM CUSTOM SCROLLER -->
    <div id="tableScrollBar" class="overflow-x-auto px-6 pb-4 custom-scrollbar">
      <div id="tableScrollBarInner" class="h-1"></div>
    </div>

    <div id="noResults" class="p-4 text-center text-gray-500" style="display:none;">
      Tidak ada paket layanan yang ditemukan
    </div>
  </div>
</div>

<style>
/* Sembunyikan scrollbar native tapi tetap bisa scroll */
.hide-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
  display: none;  /* Chrome, Safari, Opera */
}

/* Custom scrollbar untuk bottom scroller */
.custom-scrollbar::-webkit-scrollbar {
  height: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Firefox */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #888 #f1f1f1;
}
</style>

<script>
// ================= Filter & Stats =================
function filterServices() {
  var searchValue = document.getElementById('serviceSearch').value.toLowerCase();
  var tipeValue   = document.getElementById('tipeFilter').value.toLowerCase();
  var statusValue = document.getElementById('statusFilter').value.toLowerCase();
  var hargaValue  = document.getElementById('hargaFilter').value.toLowerCase();

  var rows = document.getElementsByClassName('service-row');
  var visibleCount = 0, totalRows = rows.length;

  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    var rowText   = row.innerText.toLowerCase();
    var rowTipe   = row.getAttribute('data-tipe')   || '';
    var rowStatus = row.getAttribute('data-status') || '';
    var rowHarga  = parseInt(row.getAttribute('data-harga')) || 0;

    var showRow = true;

    if (searchValue && !rowText.includes(searchValue)) showRow = false;
    if (tipeValue   && rowTipe !== tipeValue)          showRow = false;
    if (statusValue && rowStatus !== statusValue)      showRow = false;

    if (hargaValue) {
      if (hargaValue === 'murah'  && rowHarga >= 200000) showRow = false;
      if (hargaValue === 'sedang' && (rowHarga < 200000 || rowHarga > 300000)) showRow = false;
      if (hargaValue === 'mahal'  && rowHarga <= 300000) showRow = false;
    }

    row.style.display = showRow ? '' : 'none';
    if (showRow) visibleCount++;
  }

  var noResults = document.getElementById('noResults');
  if (noResults) noResults.style.display = (visibleCount === 0) ? 'block' : 'none';

  var searchStatus = document.getElementById('searchStatus');
  if (searchStatus) {
    if (searchValue || tipeValue || statusValue || hargaValue) {
      searchStatus.textContent = 'Menampilkan ' + visibleCount + ' dari ' + totalRows + ' paket layanan';
      searchStatus.style.display = 'block';
    } else {
      searchStatus.style.display = 'none';
    }
  }

  updateStats(visibleCount);
}

function updateStats(visibleCount) {
  var rows = document.getElementsByClassName('service-row');
  var aktifCount = 0;
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    if (row.style.display !== 'none' && row.getAttribute('data-status') === 'aktif') aktifCount++;
  }

  var searchValue = document.getElementById('serviceSearch').value;
  var tipeValue   = document.getElementById('tipeFilter').value;
  var statusValue = document.getElementById('statusFilter').value;
  var hargaValue  = document.getElementById('hargaFilter').value;

  if (searchValue || tipeValue || statusValue || hargaValue) {
    document.getElementById('totalPaket').textContent = visibleCount;
    document.getElementById('paketAktif').textContent = aktifCount;
  } else {
    document.getElementById('totalPaket').textContent = '7';
    document.getElementById('paketAktif').textContent = '6';
  }
}

// ================= Bottom Scroller Sync =================
function initBottomScroller() {
  const top    = document.getElementById('tableScroll');
  const bottom = document.getElementById('tableScrollBar');
  const inner  = document.getElementById('tableScrollBarInner');
  if (!top || !bottom || !inner) return;

  function resizeBar() {
    inner.style.width = top.scrollWidth + 'px';
  }

  // Sinkron dua arah
  top.addEventListener('scroll',   () => { bottom.scrollLeft = top.scrollLeft; });
  bottom.addEventListener('scroll',() => { top.scrollLeft    = bottom.scrollLeft; });

  resizeBar();

  // ResizeObserver untuk perubahan layout
  if ('ResizeObserver' in window) {
    const ro = new ResizeObserver(resizeBar);
    ro.observe(top);
  } else {
    window.addEventListener('resize', resizeBar);
  }

  // Fallback (font/asset load)
  setTimeout(resizeBar, 200);
}

// Init
document.addEventListener('DOMContentLoaded', initBottomScroller);
window.addEventListener('load', initBottomScroller);
</script>
@endsection