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
      <h1 class="text-3xl font-bold">PAKET LAYANAN</h1>
      <p class="text-gray-600">Kelola semua paket layanan yang ditawarkan</p>
      <span class="text-xs text-black bg-gray-50 px-2 py-1 rounded border inline-block mt-2">Sumber: FactLayananPeriodik</span>
    </div>
    <button onclick="openAddModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
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
      <p class="text-3xl font-bold mt-2" id="totalPaket">{{ $totalPaket }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Paket Aktif</h3>
      <p class="text-3xl font-bold mt-2" id="paketAktif">{{ $paketAktif }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-lg font-semibold text-gray-600">Total Pemesanan</h3>
      <p class="text-3xl font-bold mt-2">{{ $totalPemesanan }}</p>
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
          @forelse($paketLayanans as $paket)
            @php
              $tipe = (strpos(strtolower($paket->nama_paket), 'paket') !== false) ? 'paket' : 'tambahan';
            @endphp
            <tr class="service-row border-b hover:bg-gray-50" 
                data-tipe="{{ $tipe }}" 
                data-status="{{ $paket->is_active ? 'aktif' : 'non_aktif' }}" 
                data-harga="{{ $paket->harga_per_hari }}">
              <td class="p-4 font-medium">{{ $paket->nama_paket }}</td>
              <td class="p-4">{{ $paket->deskripsi }}</td>
              <td class="p-4">Rp {{ number_format($paket->harga_per_hari, 0, ',', '.') }}</td>
              <td class="p-4 text-center">{{ $paket->detail_penitipan_count ?? 0 }}</td>
              <td class="p-4 text-center">
                <span class="px-2 py-1 text-xs font-semibold rounded-full
                  @if($paket->is_active) text-green-700 bg-green-100
                  @else text-gray-700 bg-gray-100
                  @endif">
                  {{ $paket->is_active ? 'Aktif' : 'Non-Aktif' }}
                </span>
              </td>
              <td class="p-4">
                <div class="flex items-center gap-2">
                  <button onclick="openEditModal({{ $paket->id_paket }})" class="text-blue-600 hover:underline text-sm">Edit</button>
                  <button onclick="openViewModal({{ $paket->id_paket }})" class="text-gray-600 hover:underline text-sm">Lihat</button>
                  <button onclick="toggleStatus({{ $paket->id_paket }}, {{ $paket->is_active ? 'false' : 'true' }})" 
                    class="text-{{ $paket->is_active ? 'red' : 'green' }}-600 hover:underline text-sm">
                    {{ $paket->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                  </button>
                  <button onclick="deletePaket({{ $paket->id_paket }})" class="text-red-600 hover:underline text-sm">Hapus</button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="p-8 text-center text-gray-500">
                <p>Belum ada paket layanan</p>
              </td>
            </tr>
          @endforelse
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

<!-- Modal Tambah Paket -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Tambah Paket Layanan</h2>
      <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <form action="{{ route('admin.service.store') }}" method="POST">
      @csrf
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket *</label>
          <input type="text" name="nama_paket" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Contoh: Paket Standard">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
          <textarea name="deskripsi" required rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Deskripsi paket layanan"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Hari *</label>
          <input type="number" name="harga_per_hari" required min="0" step="1000"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="150000">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
          <textarea name="fasilitas" rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Fasilitas yang disediakan (opsional)"></textarea>
        </div>

        <div>
          <label class="flex items-center">
            <input type="checkbox" name="is_active" value="1" checked class="mr-2">
            <span class="text-sm text-gray-700">Paket Aktif</span>
          </label>
        </div>
      </div>

      <div class="flex gap-4 mt-6">
        <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
          Simpan Paket
        </button>
        <button type="button" onclick="closeAddModal()" class="flex-1 bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Paket -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Edit Paket Layanan</h2>
      <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket *</label>
          <input type="text" id="edit_nama_paket" name="nama_paket" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
          <textarea id="edit_deskripsi" name="deskripsi" required rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Hari *</label>
          <input type="number" id="edit_harga_per_hari" name="harga_per_hari" required min="0" step="1000"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
          <textarea id="edit_fasilitas" name="fasilitas" rows="3" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div>
          <label class="flex items-center">
            <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="mr-2">
            <span class="text-sm text-gray-700">Paket Aktif</span>
          </label>
        </div>
      </div>

      <div class="flex gap-4 mt-6">
        <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
          Update Paket
        </button>
        <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Lihat Detail -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Detail Paket Layanan</h2>
      <button onclick="closeViewModal()" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-500 mb-1">Nama Paket</label>
        <p id="view_nama_paket" class="text-lg font-semibold"></p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-500 mb-1">Deskripsi</label>
        <p id="view_deskripsi" class="text-gray-700"></p>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-500 mb-1">Harga per Hari</label>
          <p id="view_harga_per_hari" class="text-lg font-semibold text-green-600"></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
          <p id="view_status"></p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-500 mb-1">Fasilitas</label>
        <p id="view_fasilitas" class="text-gray-700"></p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-500 mb-1">Total Pemesanan</label>
        <p id="view_pemesanan" class="text-lg font-semibold"></p>
      </div>
    </div>

    <div class="mt-6">
      <button onclick="closeViewModal()" class="w-full bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
        Tutup
      </button>
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
// ================= Modal Functions =================
function openAddModal() {
  document.getElementById('addModal').classList.remove('hidden');
  document.getElementById('addModal').classList.add('flex');
}

function closeAddModal() {
  document.getElementById('addModal').classList.remove('flex');
  document.getElementById('addModal').classList.add('hidden');
}

function openEditModal(id) {
  // Fetch data paket dan populate form
  fetch(`/admin/paket-layanan/${id}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('edit_nama_paket').value = data.nama_paket;
      document.getElementById('edit_deskripsi').value = data.deskripsi;
      document.getElementById('edit_harga_per_hari').value = data.harga_per_hari;
      document.getElementById('edit_fasilitas').value = data.fasilitas || '';
      document.getElementById('edit_is_active').checked = data.is_active;
      document.getElementById('editForm').action = `/admin/paket-layanan/${id}`;
      
      document.getElementById('editModal').classList.remove('hidden');
      document.getElementById('editModal').classList.add('flex');
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal memuat data paket');
    });
}

function closeEditModal() {
  document.getElementById('editModal').classList.remove('flex');
  document.getElementById('editModal').classList.add('hidden');
}

function openViewModal(id) {
  // Fetch data paket dan display
  fetch(`/admin/paket-layanan/${id}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('view_nama_paket').textContent = data.nama_paket;
      document.getElementById('view_deskripsi').textContent = data.deskripsi;
      document.getElementById('view_harga_per_hari').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.harga_per_hari);
      document.getElementById('view_status').innerHTML = data.is_active 
        ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>'
        : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Non-Aktif</span>';
      document.getElementById('view_fasilitas').textContent = data.fasilitas || '-';
      document.getElementById('view_pemesanan').textContent = data.detail_penitipan_count + ' kali';
      
      document.getElementById('viewModal').classList.remove('hidden');
      document.getElementById('viewModal').classList.add('flex');
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal memuat data paket');
    });
}

function closeViewModal() {
  document.getElementById('viewModal').classList.remove('flex');
  document.getElementById('viewModal').classList.add('hidden');
}

function toggleStatus(id, newStatus) {
  if (confirm('Apakah Anda yakin ingin mengubah status paket ini?')) {
    fetch(`/admin/paket-layanan/${id}/toggle`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ is_active: newStatus })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('Gagal mengubah status');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan');
    });
  }
}

function deletePaket(id) {
  if (confirm('Apakah Anda yakin ingin menghapus paket layanan ini? Tindakan ini tidak dapat dibatalkan.')) {
    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/paket-layanan/${id}`;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
  }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
  const modals = ['addModal', 'editModal', 'viewModal'];
  modals.forEach(modalId => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          modal.classList.remove('flex');
          modal.classList.add('hidden');
        }
      });
    }
  });
});

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
    document.getElementById('totalPaket').textContent = '{{ $totalPaket }}';
    document.getElementById('paketAktif').textContent = '{{ $paketAktif }}';
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