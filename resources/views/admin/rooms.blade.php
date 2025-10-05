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
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Kondisi Sehat</h3>
      <p class="text-3xl font-bold mt-2">0</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Perlu Perhatian</h3>
      <p class="text-3xl font-bold mt-2">0</p>
    </div>
  </div>

  <!-- Search and Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4 mb-4">
      <input
        type="text"
        id="updateSearch"
        placeholder="Cari ID update, nama hewan, atau staff"
        class="flex-grow w-full sm:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        onkeyup="searchFunction()"
      >

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

    <!-- SCROLL AREA -->
    <div class="relative">
      <div class="max-h-[400px] overflow-y-scroll overflow-x-auto scrollbar-custom">
        <table class="w-full min-w-max">
          <thead class="bg-gray-50 text-left text-sm text-gray-600 sticky top-0 z-10">
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
            <!-- Data update kondisi akan muncul di sini -->
          </tbody>
        </table>

        <div id="noResults" class="p-4 text-center text-gray-500" style="display: none;">
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
// Fungsi search untuk halaman update kondisi
function searchFunction() {
  console.log('Search function called for updates!');

  // Ambil input values
  var searchValue  = document.getElementById('updateSearch').value.toLowerCase();
  var statusValue  = document.getElementById('statusFilter').value.toLowerCase();
  var staffValue   = document.getElementById('staffFilter').value.toLowerCase();
  var kondisiValue = document.getElementById('kondisiFilter').value.toLowerCase();
  var dateValue    = document.getElementById('dateFilter').value;

  // Ambil semua rows
  var rows = document.getElementsByClassName('update-row');
  var visibleCount = 0;

  // Loop setiap row
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];

    // Ambil teks dari row dan data attributes
    var rowText   = row.innerText.toLowerCase();
    var rowStatus = row.getAttribute('data-status')  || '';
    var rowStaff  = row.getAttribute('data-staff')   || '';
    var rowKondisi= row.getAttribute('data-kondisi') || '';
    var rowDate   = row.getAttribute('data-date')    || '';

    // Check kondisi
    var showRow = true;

    if (searchValue && !rowText.includes(searchValue)) showRow = false;
    if (statusValue && rowStatus !== statusValue)      showRow = false;
    if (staffValue && rowStaff !== staffValue)         showRow = false;
    if (kondisiValue && rowKondisi !== kondisiValue)   showRow = false;
    if (dateValue && rowDate !== dateValue)            showRow = false;

    row.style.display = showRow ? '' : 'none';
    if (showRow) visibleCount++;
  }

  // Show/hide no results
  var noResults = document.getElementById('noResults');
  if (noResults) noResults.style.display = (visibleCount === 0) ? 'block' : 'none';

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
}

// Test saat halaman dimuat
console.log('Update kondisi search script loaded successfully!');
</script>
@endsection