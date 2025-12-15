@extends('admin.layouts.app')

@section('content')
<div class="flex flex-col h-full">
  <!-- Flash Messages -->
  @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ session('error') }}</span>
    </div>
  @endif

  <!-- Header -->
  <header class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-3xl font-bold">UPDATE KONDISI</h1>
      <p class="text-gray-600">Kelola update kondisi hewan dalam penitipan</p>
      <span class="text-xs text-black bg-gray-50 px-2 py-1 rounded border inline-block mt-2">Sumber: Operational (UpdateKondisi) & Penitipan</span>
    </div>
    <button onclick="openModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
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
      <p class="text-3xl font-bold mt-2">{{ $sehatCount }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Perlu Perhatian</h3>
      <p class="text-3xl font-bold mt-2">{{ $perluPerhatianCount }}</p>
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
        @foreach($staffMembers as $staff)
            <option value="{{ strtolower(str_replace(' ', '_', $staff->nama_lengkap)) }}">{{ $staff->nama_lengkap }}</option>
        @endforeach
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
              <th class="p-4">Aksi</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($updateKondisis as $update)
              <tr class="update-row border-b hover:bg-gray-50"
                  data-status="{{ strtolower($update->penitipan->status) }}"
                  data-staff="{{ strtolower(str_replace(' ', '_', $update->staff->nama_lengkap)) }}"
                  data-kondisi="{{ strtolower(str_replace(' ', '_', $update->kondisi_hewan)) }}"
                  data-date="{{ $update->waktu_update->format('Y-m-d') }}">
                <td class="p-4">UPD-{{ str_pad($update->id_update, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="p-4">
                  <div>
                    <p class="font-medium">PNT-{{ str_pad($update->penitipan->id_penitipan, 4, '0', STR_PAD_LEFT) }}</p>
                    <span class="text-xs px-2 py-1 rounded-full
                      @if($update->penitipan->status == 'aktif') bg-green-100 text-green-700
                      @else bg-gray-100 text-gray-700
                      @endif">
                      {{ ucfirst($update->penitipan->status) }}
                    </span>
                  </div>
                </td>
                <td class="p-4">
                  <div>
                    <p class="font-semibold">{{ $update->penitipan->hewan->nama_hewan }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst($update->penitipan->hewan->jenis_hewan) }}</p>
                  </div>
                </td>
                <td class="p-4">
                  <div>
                    <p class="text-sm">{{ $update->staff->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ $update->staff->role }}</p>
                  </div>
                </td>
                <td class="p-4">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full
                    @if(strtolower($update->kondisi_hewan) == 'sehat') bg-green-100 text-green-700
                    @else bg-yellow-100 text-yellow-700
                    @endif">
                    {{ ucfirst($update->kondisi_hewan) }}
                  </span>
                </td>
                <td class="p-4 max-w-md">
                  <div class="aktivitas-text">
                    <p class="text-sm aktivitas-short">{{ Str::limit($update->aktivitas_hari_ini, 40) }}</p>
                    <p class="text-sm aktivitas-full hidden">{{ $update->aktivitas_hari_ini }}</p>
                    @if(strlen($update->aktivitas_hari_ini) > 40)
                      <button onclick="toggleAktivitas(this)" class="text-blue-600 hover:text-blue-800 text-xs mt-1">
                        Lihat selengkapnya
                      </button>
                    @endif
                  </div>
                  @if($update->catatan_staff)
                    <div class="catatan-text mt-2">
                      <p class="text-xs text-gray-500 catatan-short">{{ Str::limit($update->catatan_staff, 30) }}</p>
                      <p class="text-xs text-gray-500 catatan-full hidden">{{ $update->catatan_staff }}</p>
                      @if(strlen($update->catatan_staff) > 30)
                        <button onclick="toggleCatatan(this)" class="text-blue-600 hover:text-blue-800 text-xs mt-1">
                          Lihat selengkapnya
                        </button>
                      @endif
                    </div>
                  @endif
                </td>
                <td class="p-4">
                  <div>
                    <p class="text-sm">{{ $update->waktu_update->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $update->waktu_update->format('H:i') }}</p>
                  </div>
                </td>
                <td class="p-4">
                  <div class="flex gap-2">
                    <button onclick="openEditModal({{ $update->id_update }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                      </svg>
                    </button>
                    <button onclick="confirmDelete({{ $update->id_update }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="p-8 text-center text-gray-500">
                  <p>Belum ada update kondisi</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div id="noResults" class="p-4 text-center text-gray-500" style="display: none;">
          Tidak ada hasil ditemukan
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Update -->
<div id="addUpdateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Tambah Update Kondisi</h2>
      <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <div class="space-y-4">
        <!-- Pilih Penitipan -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Penitipan Aktif *</label>
          <select name="id_penitipan" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Pilih Penitipan</option>
            @foreach($aktivePenitipan as $penitipan)
              <option value="{{ $penitipan->id_penitipan }}">
                PNT-{{ str_pad($penitipan->id_penitipan, 4, '0', STR_PAD_LEFT) }} - 
                {{ $penitipan->hewan->nama_hewan }} 
                ({{ $penitipan->pemilik->nama_lengkap }})
              </option>
            @endforeach
          </select>
        </div>

        <!-- Kondisi Hewan -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Hewan *</label>
          <select name="kondisi_hewan" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Pilih Kondisi</option>
            <option value="sehat">Sehat</option>
            <option value="perlu perhatian">Perlu Perhatian</option>
            <option value="sakit">Sakit</option>
          </select>
        </div>

        <!-- Aktivitas Hari Ini -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Aktivitas Hari Ini *</label>
          <textarea name="aktivitas_hari_ini" required rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Contoh: Makan 3x sehari, bermain di taman, tidur nyenyak"></textarea>
        </div>

        <!-- Catatan Staff -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Staff</label>
          <textarea name="catatan_staff" rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Catatan tambahan (opsional)"></textarea>
        </div>

        <!-- Foto Hewan -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Foto Hewan</label>
          <input type="file" name="foto_hewan" accept="image/*"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (max 2MB)</p>
        </div>
      </div>

      <div class="flex gap-4 mt-6">
        <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
          Simpan Update
        </button>
        <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Update -->
<div id="editUpdateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Edit Update Kondisi</h2>
      <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <form id="editUpdateForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
      <div class="space-y-4">
        <!-- Pilih Penitipan -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Penitipan Aktif *</label>
          <select name="id_penitipan" id="edit_id_penitipan" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Pilih Penitipan</option>
            @foreach($aktivePenitipan as $penitipan)
              <option value="{{ $penitipan->id_penitipan }}">
                PNT-{{ str_pad($penitipan->id_penitipan, 4, '0', STR_PAD_LEFT) }} - 
                {{ $penitipan->hewan->nama_hewan }} 
                ({{ $penitipan->pemilik->nama_lengkap }})
              </option>
            @endforeach
          </select>
        </div>

        <!-- Kondisi Hewan -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Hewan *</label>
          <select name="kondisi_hewan" id="edit_kondisi_hewan" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Pilih Kondisi</option>
            <option value="sehat">Sehat</option>
            <option value="perlu perhatian">Perlu Perhatian</option>
            <option value="sakit">Sakit</option>
          </select>
        </div>

        <!-- Aktivitas Hari Ini -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Aktivitas Hari Ini *</label>
          <textarea name="aktivitas_hari_ini" id="edit_aktivitas_hari_ini" required rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Contoh: Makan 3x sehari, bermain di taman, tidur nyenyak"></textarea>
        </div>

        <!-- Catatan Staff -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Staff</label>
          <textarea name="catatan_staff" id="edit_catatan_staff" rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Catatan tambahan (opsional)"></textarea>
        </div>

        <!-- Foto Hewan -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Foto Hewan</label>
          <input type="file" name="foto_hewan" accept="image/*"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (max 2MB) - Kosongkan jika tidak ingin mengubah foto</p>
          <div id="current_foto" class="mt-2"></div>
        </div>
      </div>

      <div class="flex gap-4 mt-6">
        <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
          Perbarui Update
        </button>
        <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
    <div class="flex justify-center mb-4">
      <div class="rounded-full bg-red-100 p-3">
        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
      </div>
    </div>
    <h3 class="text-xl font-bold text-center mb-2">Konfirmasi Hapus</h3>
    <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus update kondisi ini? Tindakan ini tidak dapat dibatalkan.</p>
    
    <form id="deleteForm" method="POST">
      @csrf
      @method('DELETE')
      
      <div class="flex gap-4">
        <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
          Ya, Hapus
        </button>
        <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
          Batal
        </button>
      </div>
    </form>
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
// Modal functions
function openModal() {
  document.getElementById('addUpdateModal').classList.remove('hidden');
  document.getElementById('addUpdateModal').classList.add('flex');
}

function closeModal() {
  document.getElementById('addUpdateModal').classList.remove('flex');
  document.getElementById('addUpdateModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('addUpdateModal');
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeModal();
    }
  });

  const editModal = document.getElementById('editUpdateModal');
  editModal.addEventListener('click', function(e) {
    if (e.target === editModal) {
      closeEditModal();
    }
  });

  const deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('click', function(e) {
    if (e.target === deleteModal) {
      closeDeleteModal();
    }
  });
});

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

// Fungsi toggle untuk aktivitas
function toggleAktivitas(button) {
  const parent = button.closest('.aktivitas-text');
  const shortText = parent.querySelector('.aktivitas-short');
  const fullText = parent.querySelector('.aktivitas-full');
  
  if (shortText.classList.contains('hidden')) {
    shortText.classList.remove('hidden');
    fullText.classList.add('hidden');
    button.textContent = 'Lihat selengkapnya';
  } else {
    shortText.classList.add('hidden');
    fullText.classList.remove('hidden');
    button.textContent = 'Lihat lebih sedikit';
  }
}

// Fungsi toggle untuk catatan
function toggleCatatan(button) {
  const parent = button.closest('.catatan-text');
  const shortText = parent.querySelector('.catatan-short');
  const fullText = parent.querySelector('.catatan-full');
  
  if (shortText.classList.contains('hidden')) {
    shortText.classList.remove('hidden');
    fullText.classList.add('hidden');
    button.textContent = 'Lihat selengkapnya';
  } else {
    shortText.classList.add('hidden');
    fullText.classList.remove('hidden');
    button.textContent = 'Lihat lebih sedikit';
  }
}

// Edit Modal Functions
function openEditModal(updateId) {
  // Fetch update kondisi data
  fetch(`/admin/update-kondisi/${updateId}`)
    .then(response => response.json())
    .then(data => {
      // Set form action
      document.getElementById('editUpdateForm').action = `/admin/update-kondisi/${updateId}`;
      
      // Populate form fields
      document.getElementById('edit_id_penitipan').value = data.id_penitipan;
      document.getElementById('edit_kondisi_hewan').value = data.kondisi_hewan;
      document.getElementById('edit_aktivitas_hari_ini').value = data.aktivitas_hari_ini;
      document.getElementById('edit_catatan_staff').value = data.catatan_staff || '';
      
      // Show current photo if exists
      const currentFotoDiv = document.getElementById('current_foto');
      if (data.foto_hewan) {
        currentFotoDiv.innerHTML = `
          <p class="text-xs text-gray-600 mb-1">Foto saat ini:</p>
          <img src="/${data.foto_hewan}" alt="Current photo" class="w-32 h-32 object-cover rounded-lg">
        `;
      } else {
        currentFotoDiv.innerHTML = '';
      }
      
      // Show modal
      document.getElementById('editUpdateModal').classList.remove('hidden');
      document.getElementById('editUpdateModal').classList.add('flex');
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal memuat data update kondisi');
    });
}

function closeEditModal() {
  document.getElementById('editUpdateModal').classList.remove('flex');
  document.getElementById('editUpdateModal').classList.add('hidden');
  document.getElementById('editUpdateForm').reset();
  document.getElementById('current_foto').innerHTML = '';
}

// Delete Modal Functions
function confirmDelete(updateId) {
  document.getElementById('deleteForm').action = `/admin/update-kondisi/${updateId}`;
  document.getElementById('deleteModal').classList.remove('hidden');
  document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
  document.getElementById('deleteModal').classList.remove('flex');
  document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection