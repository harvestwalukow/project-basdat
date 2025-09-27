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
        <p class="text-3xl font-bold mt-2" id="totalPaket">5</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Paket Aktif</h3>
        <p class="text-3xl font-bold mt-2" id="paketAktif">3</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Pemesanan</h3>
        <p class="text-3xl font-bold mt-2">45</p>
    </div>
  </div>

  <!-- Search and Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
      <div class="flex flex-wrap items-center gap-4">
        <input type="text" id="serviceSearch" 
          placeholder="Cari nama paket atau deskripsi" 
          class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
          onkeyup="filterServices()">
        
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
          <option value="murah">< Rp 200.000</option>
          <option value="sedang">Rp 200.000 - Rp 300.000</option>
          <option value="mahal">> Rp 300.000</option>
        </select>
      </div>
      
    </div>
    
    <!-- Search Status Info -->
    <div id="searchStatus" class="text-sm text-gray-600" style="display: none;"></div>
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
        <tbody id="tableBody">
          <tr class="service-row border-b hover:bg-gray-50" 
              data-tipe="paket" 
              data-status="aktif" 
              data-harga="150000">
            <td class="p-4 font-medium">Paket Basic</td>
            <td class="p-4">Penitipan standar dengan fasilitas dasar</td>
            <td class="p-4">Rp 150.000</td>
            <td class="p-4 text-center">25</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <a href="#" class="text-blue-600 hover:underline text-sm">Edit</a>
                <a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a>
              </div>
            </td>
          </tr>
          <tr class="service-row border-b hover:bg-gray-50" 
              data-tipe="paket" 
              data-status="aktif" 
              data-harga="250000">
            <td class="p-4 font-medium">Paket Premium</td>
            <td class="p-4">Penitipan premium dengan fasilitas lengkap dan grooming</td>
            <td class="p-4">Rp 250.000</td>
            <td class="p-4 text-center">15</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <a href="#" class="text-blue-600 hover:underline text-sm">Edit</a>
                <a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a>
              </div>
            </td>
          </tr>
          <tr class="service-row border-b hover:bg-gray-50" 
              data-tipe="paket" 
              data-status="non_aktif" 
              data-harga="350000">
            <td class="p-4 font-medium">Paket Deluxe</td>
            <td class="p-4">Penitipan mewah dengan layanan eksklusif dan spa</td>
            <td class="p-4">Rp 350.000</td>
            <td class="p-4 text-center">5</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Non-Aktif</span></td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <a href="#" class="text-blue-600 hover:underline text-sm">Edit</a>
                <a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a>
              </div>
            </td>
          </tr>
          <tr class="service-row border-b hover:bg-gray-50" 
              data-tipe="tambahan" 
              data-status="aktif" 
              data-harga="75000">
            <td class="p-4 font-medium">Grooming Tambahan</td>
            <td class="p-4">Layanan grooming dan perawatan tambahan</td>
            <td class="p-4">Rp 75.000</td>
            <td class="p-4 text-center">30</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <a href="#" class="text-blue-600 hover:underline text-sm">Edit</a>
                <a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a>
              </div>
            </td>
          </tr>
          <tr class="service-row border-b hover:bg-gray-50" 
              data-tipe="tambahan" 
              data-status="aktif" 
              data-harga="50000">
            <td class="p-4 font-medium">Makanan Premium</td>
            <td class="p-4">Makanan premium dan suplemen kesehatan hewan</td>
            <td class="p-4">Rp 50.000</td>
            <td class="p-4 text-center">20</td>
            <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            <td class="p-4">
              <div class="flex items-center gap-2">
                <a href="#" class="text-blue-600 hover:underline text-sm">Edit</a>
                <a href="#" class="text-gray-600 hover:underline text-sm">Lihat</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div id="noResults" class="p-4 text-center text-gray-500" style="display: none;">
        Tidak ada paket layanan yang ditemukan
      </div>
    </div>
  </div>
</div>

<script>
// Fungsi filter untuk halaman paket layanan
function filterServices() {
  console.log('Filter services called!');
  
  // Ambil input values
  var searchValue = document.getElementById('serviceSearch').value.toLowerCase();
  var tipeValue = document.getElementById('tipeFilter').value.toLowerCase();
  var statusValue = document.getElementById('statusFilter').value.toLowerCase();
  var hargaValue = document.getElementById('hargaFilter').value.toLowerCase();
  
  console.log('Filter values:', {searchValue, tipeValue, statusValue, hargaValue});
  
  // Ambil semua rows
  var rows = document.getElementsByClassName('service-row');
  var visibleCount = 0;
  var totalRows = rows.length;
  
  console.log('Total rows found:', totalRows);
  
  // Loop setiap row
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    
    // Ambil teks dari row dan data attributes
    var rowText = row.innerText.toLowerCase();
    var rowTipe = row.getAttribute('data-tipe') || '';
    var rowStatus = row.getAttribute('data-status') || '';
    var rowHarga = parseInt(row.getAttribute('data-harga')) || 0;
    
    // Check kondisi
    var showRow = true;
    
    // Check search text (nama paket dan deskripsi)
    if (searchValue && !rowText.includes(searchValue)) {
      showRow = false;
    }
    
    // Check tipe filter
    if (tipeValue && rowTipe !== tipeValue) {
      showRow = false;
    }
    
    // Check status filter
    if (statusValue && rowStatus !== statusValue) {
      showRow = false;
    }
    
    // Check harga filter
    if (hargaValue) {
      switch(hargaValue) {
        case 'murah':
          if (rowHarga >= 200000) showRow = false;
          break;
        case 'sedang':
          if (rowHarga < 200000 || rowHarga > 300000) showRow = false;
          break;
        case 'mahal':
          if (rowHarga <= 300000) showRow = false;
          break;
      }
    }
    
    // Show/hide row
    if (showRow) {
      row.style.display = '';
      visibleCount++;
    } else {
      row.style.display = 'none';
    }
    
    // Debug first row
    if (i === 0) {
      console.log('First row debug:', {
        rowText: rowText.substring(0, 50),
        rowTipe,
        rowStatus,
        rowHarga,
        showRow
      });
    }
  }
  
  // Show/hide no results
  var noResults = document.getElementById('noResults');
  if (noResults) {
    if (visibleCount === 0) {
      noResults.style.display = 'block';
    } else {
      noResults.style.display = 'none';
    }
  }
  
  // Update search status
  var searchStatus = document.getElementById('searchStatus');
  if (searchStatus) {
    if (searchValue || tipeValue || statusValue || hargaValue) {
      searchStatus.textContent = 'Menampilkan ' + visibleCount + ' dari ' + totalRows + ' paket layanan';
      searchStatus.style.display = 'block';
    } else {
      searchStatus.style.display = 'none';
    }
  }
  
  // Update stats dinamis
  updateStats(visibleCount);
  
  console.log('Visible rows:', visibleCount);
}

// Fungsi untuk update statistik secara dinamis
function updateStats(visibleCount) {
  var rows = document.getElementsByClassName('service-row');
  var aktifCount = 0;
  
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    if (row.style.display !== 'none' && row.getAttribute('data-status') === 'aktif') {
      aktifCount++;
    }
  }
  
  // Update tampilan stats jika sedang ada filter
  var searchValue = document.getElementById('serviceSearch').value;
  var tipeValue = document.getElementById('tipeFilter').value;
  var statusValue = document.getElementById('statusFilter').value;
  var hargaValue = document.getElementById('hargaFilter').value;
  
  if (searchValue || tipeValue || statusValue || hargaValue) {
    document.getElementById('totalPaket').textContent = visibleCount;
    document.getElementById('paketAktif').textContent = aktifCount;
  } else {
    // Reset ke nilai asli jika tidak ada filter
    document.getElementById('totalPaket').textContent = '5';
    document.getElementById('paketAktif').textContent = '3';
  }
}

// Test saat halaman dimuat
console.log('Service page search script loaded successfully!');
</script>
@endsection