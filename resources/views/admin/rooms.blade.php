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

  <!-- Search and Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4 mb-4">
      <input type="text" id="updateSearch" 
        placeholder="Cari ID update, nama hewan, atau staff" 
        class="flex-grow w-full sm:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        onkeyup="searchFunction()">
      
      <select id="statusFilter" class="px-4 py-2 border rounded-lg" onchange="searchFunction()">
        <option value="">Semua Penitipan</option>
        <option value="aktif">Aktif</option>
        <option value="selesai">Selesai</option>
      </select>
      
      <select id="staffFilter" class="px-4 py-2 border rounded-lg" onchange="searchFunction()">
        <option value="">Semua Staff</option>
        <option value="staff_a">Staff A</option>
        <option value="staff_b">Staff B</option>
      </select>
      
      <select id="kondisiFilter" class="px-4 py-2 border rounded-lg" onchange="searchFunction()">
        <option value="">Semua Kondisi</option>
        <option value="sehat">Sehat</option>
        <option value="perlu_perhatian">Perlu Perhatian</option>
      </select>
      
      <input type="date" id="dateFilter" class="px-4 py-2 border rounded-lg" onchange="searchFunction()">
    </div>
    
    <!-- Search Status Info -->
    <div id="searchStatus" class="text-sm text-gray-600" style="display: none;"></div>
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
        <tbody id="tableBody">
          <tr class="update-row border-b hover:bg-gray-50" 
              data-status="aktif" 
              data-staff="staff_a" 
              data-kondisi="sehat"
              data-date="2025-09-28">
            <td class="p-4 font-mono text-sm">UK-001</td>
            <td class="p-4 font-mono text-sm">PT-001</td>
            <td class="p-4 font-medium">Buddy (Anjing)</td>
            <td class="p-4">Staff A</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sehat</span></td>
            <td class="p-4 text-sm">Makan normal, bermain aktif</td>
            <td class="p-4">28 Sep 2025 14:30</td>
          </tr>
          <tr class="update-row border-b hover:bg-gray-50" 
              data-status="aktif" 
              data-staff="staff_b" 
              data-kondisi="perlu_perhatian"
              data-date="2025-09-28">
            <td class="p-4 font-mono text-sm">UK-002</td>
            <td class="p-4 font-mono text-sm">PT-002</td>
            <td class="p-4 font-medium">Milo (Kucing)</td>
            <td class="p-4">Staff B</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Perlu Perhatian</span></td>
            <td class="p-4 text-sm">Kurang nafsu makan</td>
            <td class="p-4">28 Sep 2025 15:45</td>
          </tr>
          <tr class="update-row border-b hover:bg-gray-50" 
              data-status="aktif" 
              data-staff="staff_a" 
              data-kondisi="sehat"
              data-date="2025-09-28">
            <td class="p-4 font-mono text-sm">UK-003</td>
            <td class="p-4 font-mono text-sm">PT-003</td>
            <td class="p-4 font-medium">Leo (Anjing)</td>
            <td class="p-4">Staff A</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sehat</span></td>
            <td class="p-4 text-sm">Tidur nyenyak, kondisi baik</td>
            <td class="p-4">28 Sep 2025 16:20</td>
          </tr>
          <tr class="update-row border-b hover:bg-gray-50" 
              data-status="aktif" 
              data-staff="staff_b" 
              data-kondisi="sehat"
              data-date="2025-09-27">
            <td class="p-4 font-mono text-sm">UK-004</td>
            <td class="p-4 font-mono text-sm">PT-004</td>
            <td class="p-4 font-medium">Coco (Kucing)</td>
            <td class="p-4">Staff B</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sehat</span></td>
            <td class="p-4 text-sm">Kondisi prima, sangat aktif</td>
            <td class="p-4">27 Sep 2025 18:15</td>
          </tr>
          <tr class="update-row border-b hover:bg-gray-50" 
              data-status="selesai" 
              data-staff="staff_a" 
              data-kondisi="sehat"
              data-date="2025-09-26">
            <td class="p-4 font-mono text-sm">UK-005</td>
            <td class="p-4 font-mono text-sm">PT-005</td>
            <td class="p-4 font-medium">Max (Anjing)</td>
            <td class="p-4">Staff A</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sehat</span></td>
            <td class="p-4 text-sm">Siap dipulangkan ke pemilik</td>
            <td class="p-4">26 Sep 2025 10:00</td>
          </tr>
        </tbody>
      </table>
      <div id="noResults" class="p-4 text-center text-gray-500" style="display: none;">
        Tidak ada hasil ditemukan
      </div>
    </div>
  </div>
</div>

<script>
// Fungsi search untuk halaman update kondisi
function searchFunction() {
  console.log('Search function called for updates!');
  
  // Ambil input values
  var searchValue = document.getElementById('updateSearch').value.toLowerCase();
  var statusValue = document.getElementById('statusFilter').value.toLowerCase();
  var staffValue = document.getElementById('staffFilter').value.toLowerCase();
  var kondisiValue = document.getElementById('kondisiFilter').value.toLowerCase();
  var dateValue = document.getElementById('dateFilter').value;
  
  console.log('Search values:', {searchValue, statusValue, staffValue, kondisiValue, dateValue});
  
  // Ambil semua rows
  var rows = document.getElementsByClassName('update-row');
  var visibleCount = 0;
  
  console.log('Total rows found:', rows.length);
  
  // Loop setiap row
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    
    // Ambil teks dari row dan data attributes
    var rowText = row.innerText.toLowerCase();
    var rowStatus = row.getAttribute('data-status') || '';
    var rowStaff = row.getAttribute('data-staff') || '';
    var rowKondisi = row.getAttribute('data-kondisi') || '';
    var rowDate = row.getAttribute('data-date') || '';
    
    // Check kondisi
    var showRow = true;
    
    // Check search text (ID update, nama hewan, staff)
    if (searchValue && !rowText.includes(searchValue)) {
      showRow = false;
    }
    
    // Check status filter
    if (statusValue && rowStatus !== statusValue) {
      showRow = false;
    }
    
    // Check staff filter
    if (staffValue && rowStaff !== staffValue) {
      showRow = false;
    }
    
    // Check kondisi filter
    if (kondisiValue && rowKondisi !== kondisiValue) {
      showRow = false;
    }
    
    // Check date filter
    if (dateValue && rowDate !== dateValue) {
      showRow = false;
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
        rowStatus,
        rowStaff,
        rowKondisi,
        rowDate,
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
    if (searchValue || statusValue || staffValue || kondisiValue || dateValue) {
      searchStatus.textContent = 'Menampilkan ' + visibleCount + ' dari ' + rows.length + ' update kondisi';
      searchStatus.style.display = 'block';
    } else {
      searchStatus.style.display = 'none';
    }
  }
  
  console.log('Visible rows:', visibleCount);
}

// Test saat halaman dimuat
console.log('Update kondisi search script loaded successfully!');
</script>
@endsection