@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <header class="mb-2">
    <h1 class="text-3xl font-bold">OPERASIONAL</h1>
    <p class="text-gray-500">Kelola semua penitipan dan reservasi hewan</p>
  </header>

  <!-- Statistik Ringkas -->
  <div class="mb-2 flex justify-end">
     <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-medium">fact_kapasitas_harian</span>
  </div>
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

  <!-- Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4">
      <input type="text" id="petSearch"
        placeholder="Cari nama hewan, pemilik, atau ID"
        class="flex-grow w-full sm:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        onkeyup="searchFunction()">
      <div class="relative">
        <select id="jenisFilter" class="appearance-none px-4 py-2 pr-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" onchange="searchFunction()">
          <option value="">Semua Jenis</option>
          <option value="anjing">Anjing</option>
          <option value="kucing">Kucing</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>

      <div class="relative">
        <select id="layananFilter" class="appearance-none px-4 py-2 pr-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" onchange="searchFunction()">
          <option value="">Semua Layanan</option>
          @foreach($filterLayanan as $layanan)
            <option value="{{ strtolower($layanan) }}">{{ $layanan }}</option>
          @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>

      <div class="relative">
        <select id="statusFilter" class="appearance-none px-4 py-2 pr-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" onchange="searchFunction()">
          <option value="">Semua Status</option>
          @foreach($filterStatus as $status)
            <option value="{{ strtolower($status) }}">{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>
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
      <div class="max-h-[500px] overflow-y-scroll overflow-x-auto scrollbar-custom">
        <table class="w-full min-w-max">
          <thead class="bg-gray-50 text-left text-sm text-gray-600 sticky top-0 z-10">
            <tr>
              <th class="p-4">Hewan</th>
              <th class="p-4">Pemilik</th>
              <th class="p-4">Detail Fisik</th>
              <th class="p-4">Kondisi Khusus</th>
              <th class="p-4">Layanan</th>
              <th class="p-4">Status</th>
              <th class="p-4">Riwayat Penitipan</th>
              <th class="p-4">Aksi</th>
            </tr>
          </thead>
          <tbody id="tableBody" class="text-sm">
            @forelse($hewans as $hewan)
              @php
                $activePenitipan = $hewan->penitipan->where('status', 'aktif')->first();
                // Get latest penitipan for status display
                $latestPenitipan = $hewan->penitipan->first();
                $statusPenitipan = $latestPenitipan ? $latestPenitipan->status : null;
                
                // Convert jenis to Indonesian for consistent filtering
                $jenisForFilter = strtolower($hewan->jenis_hewan);
                if ($jenisForFilter === 'cat') $jenisForFilter = 'kucing';
                if ($jenisForFilter === 'dog') $jenisForFilter = 'anjing';
              @endphp
              <tr class="pet-row border-b hover:bg-gray-50 bg-white" 
                  data-jenis="{{ $jenisForFilter }}" 
                  data-layanan="{{ $activeServicesString ?? '' }}" 
                  data-status="{{ $searchStatus ?? '' }}">
                <td class="p-4">
                  <div>
                    <p class="font-semibold">{{ $hewan->nama_hewan }}</p>
                    @php
                      $jenisIndonesia = $hewan->jenis_hewan;
                      if (strtolower($jenisIndonesia) === 'cat') $jenisIndonesia = 'kucing';
                      if (strtolower($jenisIndonesia) === 'dog') $jenisIndonesia = 'anjing';
                    @endphp
                    <p class="text-xs text-gray-500">{{ ucfirst($jenisIndonesia) }} • {{ $hewan->ras }}</p>
                  </div>
                </td>
                <td class="p-4">
                  <div>
                    <p class="font-medium">{{ $hewan->pemilik->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ $hewan->pemilik->no_telepon }}</p>
                  </div>
                </td>
                <td class="p-4">
                  <div class="text-xs">
                    <p>Umur: {{ $hewan->umur }} bulan</p>
                    <p>Berat: {{ $hewan->berat }} kg</p>
                    <p>{{ ucfirst($hewan->jenis_kelamin) }}</p>
                  </div>
                </td>
                <td class="p-4">
                  <p class="text-xs">{{ $hewan->kondisi_khusus ?? '-' }}</p>
                  @if($hewan->catatan_medis)
                    <p class="text-xs text-gray-500 mt-1">Catatan: {{ Str::limit($hewan->catatan_medis, 30) }}</p>
                  @endif
                </td>
                <td class="p-4">
                  @php
                    // Ambil SEMUA layanan dari penitipan aktif
                    $semuaLayanan = collect();
                    if ($activePenitipan) {
                      foreach ($activePenitipan->detailPenitipan as $detail) {
                        if ($detail->paketLayanan) {
                          $semuaLayanan->push($detail->paketLayanan->nama_paket);
                        }
                      }
                    }
                    $activeServicesString = $semuaLayanan->map(fn($item) => strtolower($item))->implode(' ');
                    // Default valid status for filter attributes
                    $searchStatus = $statusPenitipan ? strtolower($statusPenitipan) : '';
                  @endphp
                  @if($semuaLayanan->isNotEmpty())
                    <div class="flex flex-col gap-1 items-start">
                      @foreach($semuaLayanan as $layanan)
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $layanan }}</span>
                      @endforeach
                    </div>
                  @else
                    <p class="text-xs text-gray-400">-</p>
                  @endif
                </td>
                <td class="p-4">
                  @if($statusPenitipan)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                      @if($statusPenitipan == 'aktif') bg-green-100 text-green-700
                      @elseif($statusPenitipan == 'pending') bg-yellow-100 text-yellow-700
                      @elseif($statusPenitipan == 'selesai') bg-blue-100 text-blue-700
                      @else bg-red-100 text-red-700
                      @endif">
                      {{ ucfirst($statusPenitipan) }}
                    </span>
                  @else
                    <p class="text-xs text-gray-400">-</p>
                  @endif
                </td>
                <td class="p-4">
                  <p class="text-xs">{{ $hewan->penitipan->count() }} kali</p>
                  @if($hewan->penitipan->count() > 0)
                    @php
                       $lastBooking = $hewan->penitipan->first();
                       $masuk = \Carbon\Carbon::parse($lastBooking->tanggal_masuk);
                       $keluar = \Carbon\Carbon::parse($lastBooking->tanggal_keluar);
                    @endphp
                    <p class="text-xs text-gray-500">{{ $masuk->format('d M') }} - {{ $keluar->format('d M Y') }}</p>
                  @endif
                </td>
                <td class="p-4">
                  <div class="flex gap-2">
                    <button onclick="showDetailModal({{ $hewan->id_hewan }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Detail">
                      <i class="fa-solid fa-eye"></i>
                    </button>
                    <button onclick="showEditModal({{ $hewan->id_hewan }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="p-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="text-lg font-medium">Belum ada data hewan</p>
                  <p class="text-sm text-gray-400 mt-1">Tambahkan hewan baru dengan klik tombol "Tambah Hewan"</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div id="noResults" class="p-4 text-center text-gray-500 hidden">
          Tidak ada hasil ditemukan
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Modal Detail Hewan -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-bold text-gray-800">Detail Hewan</h3>
      <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <div id="detailContent" class="space-y-4">
      <!-- Content will be loaded here -->
    </div>
  </div>
</div>

<!-- Modal Edit Hewan -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-bold text-gray-800">Edit Data Hewan</h3>
      <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <form id="editForm" method="POST" action="" class="space-y-4">
      @csrf
      @method('PUT')
      
      <div id="editContent">
        <!-- Content will be loaded here -->
      </div>

      <div class="flex gap-3 pt-4">
        <button type="button" onclick="closeEditModal()"
          class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
          Batal
        </button>
        <button type="submit"
          class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
          Simpan Perubahan
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
// Fungsi filter sederhana
function searchFunction() {
  var searchValue = document.getElementById('petSearch').value.toLowerCase();
  var jenisValue  = document.getElementById('jenisFilter').value.toLowerCase();
  var layananValue= document.getElementById('layananFilter').value.toLowerCase();
  var statusValue = document.getElementById('statusFilter').value.toLowerCase();

  var rows = document.getElementsByClassName('pet-row');
  var visibleCount = 0;

  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    var rowText = row.innerText.toLowerCase();
    var rowJenis = (row.getAttribute('data-jenis') || '').toLowerCase();
    var rowLayanan = (row.getAttribute('data-layanan') || '').toLowerCase();
    var rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

    var showRow = true;

    if (searchValue && !rowText.includes(searchValue)) showRow = false;
    if (jenisValue  && rowJenis !== jenisValue)       showRow = false;
    // Check if the selected service exists in the row's services string (substring match)
    if (layananValue && !rowLayanan.includes(layananValue)) showRow = false;
    // Status might be exact match or included
    if (statusValue && rowStatus !== statusValue) showRow = false;

    row.style.display = showRow ? '' : 'none';
    if (showRow) visibleCount++;
  }

  var noResults = document.getElementById('noResults');
  if (noResults) noResults.classList.toggle('hidden', visibleCount !== 0);
}

// Store pets data for modals
const hewansData = @json($hewans ?? []);

// Helper function to convert jenis to Indonesian
function convertJenisToIndonesian(jenis) {
  const jenisLower = jenis.toLowerCase();
  if (jenisLower === 'cat') return 'Kucing';
  if (jenisLower === 'dog') return 'Anjing';
  return jenis.charAt(0).toUpperCase() + jenis.slice(1);
}

// Modal functions
function showDetailModal(id) {
  const hewan = hewansData.find(h => h.id_hewan === id);
  if (!hewan) return;

  const penitipanCount = hewan.penitipan ? hewan.penitipan.length : 0;
  const lastPenitipan = hewan.penitipan && hewan.penitipan.length > 0 ? hewan.penitipan[0].tanggal_masuk : null;
  
  // Get active penitipan layanan (SEMUA layanan yang dipilih)
  let semuaLayanan = [];
  const activePenitipan = hewan.penitipan ? hewan.penitipan.find(p => p.status === 'aktif') : null;
  if (activePenitipan && activePenitipan.detail_penitipan) {
    activePenitipan.detail_penitipan.forEach(detail => {
      if (detail.paket_layanan) {
        semuaLayanan.push(detail.paket_layanan.nama_paket);
      }
    });
  }
  
  // Fallback: check for camelCase if snake_case doesn't exist
  if (!activePenitipan?.detail_penitipan && activePenitipan?.detailPenitipan) {
    activePenitipan.detailPenitipan.forEach(detail => {
      if (detail.paketLayanan || detail.paket_layanan) {
        const paket = detail.paketLayanan || detail.paket_layanan;
        semuaLayanan.push(paket.nama_paket);
      }
    });
  }

  const content = `
    <div class="grid grid-cols-2 gap-4">
      <div class="col-span-2">
        <h4 class="text-lg font-semibold text-gray-700 mb-2">Informasi Hewan</h4>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Nama Hewan</label>
        <p class="font-semibold">${hewan.nama_hewan}</p>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Jenis</label>
        <p class="font-semibold">${convertJenisToIndonesian(hewan.jenis_hewan)}</p>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Ras</label>
        <p class="font-semibold">${hewan.ras}</p>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Umur</label>
        <p class="font-semibold">${hewan.umur} bulan</p>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Berat</label>
        <p class="font-semibold">${hewan.berat} kg</p>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Jenis Kelamin</label>
        <p class="font-semibold">${hewan.jenis_kelamin.charAt(0).toUpperCase() + hewan.jenis_kelamin.slice(1)}</p>
      </div>
      
      <div class="col-span-2">
        <label class="text-sm text-gray-500">Kondisi Khusus</label>
        <p class="font-semibold">${hewan.kondisi_khusus || '-'}</p>
      </div>
      
      <div class="col-span-2">
        <label class="text-sm text-gray-500">Catatan Medis</label>
        <p class="font-semibold">${hewan.catatan_medis || '-'}</p>
      </div>
      
      <div class="col-span-2">
        <label class="text-sm text-gray-500">Layanan Aktif</label>
        <div class="mt-1">
          ${semuaLayanan.length > 0 
            ? semuaLayanan.map(l => `<span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mr-2 mb-1">${l}</span>`).join('') 
            : '<p class="font-semibold text-gray-400">-</p>'}
        </div>
      </div>
      
      <div class="col-span-2 border-t pt-4 mt-2">
        <h4 class="text-lg font-semibold text-gray-700 mb-2">Informasi Pemilik</h4>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Nama Pemilik</label>
        <p class="font-semibold">${hewan.pemilik.nama_lengkap}</p>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Email</label>
        <p class="font-semibold">${hewan.pemilik.email}</p>
      </div>
      
      <div class="col-span-2">
        <label class="text-sm text-gray-500">No. Telepon</label>
        <p class="font-semibold">${hewan.pemilik.no_telepon}</p>
      </div>
      
      <div class="col-span-2 border-t pt-4 mt-2">
        <h4 class="text-lg font-semibold text-gray-700 mb-2">Riwayat Penitipan</h4>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Total Penitipan</label>
        <p class="font-semibold">${penitipanCount} kali</p>
      </div>
      
      <div>
        <label class="text-sm text-gray-500">Terakhir Dititipkan</label>
        <p class="font-semibold">${lastPenitipan || '-'}</p>
      </div>
    </div>
  `;

  document.getElementById('detailContent').innerHTML = content;
  document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
  document.getElementById('detailModal').classList.add('hidden');
}

function showEditModal(id) {
  const hewan = hewansData.find(h => h.id_hewan === id);
  if (!hewan) return;
  
  // Get latest booking status
  const latestBooking = hewan.penitipan && hewan.penitipan.length > 0 ? hewan.penitipan[0] : null;
  const currentStatus = latestBooking ? latestBooking.status : '';

  document.getElementById('editForm').action = '/admin/hewan/' + id + '/update';

  const content = `
    <div class="grid grid-cols-1 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Hewan</label>
        <input type="text" name="nama_hewan" value="${hewan.nama_hewan}" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Hewan</label>
          <select name="jenis_hewan" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="anjing" ${hewan.jenis_hewan.toLowerCase() === 'anjing' || hewan.jenis_hewan.toLowerCase() === 'dog' ? 'selected' : ''}>Anjing</option>
            <option value="kucing" ${hewan.jenis_hewan.toLowerCase() === 'kucing' || hewan.jenis_hewan.toLowerCase() === 'cat' ? 'selected' : ''}>Kucing</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Ras</label>
          <input type="text" name="ras" value="${hewan.ras}" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>

      <div class="grid grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Umur (bulan)</label>
          <input type="number" name="umur" value="${hewan.umur}" min="0" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
          <input type="number" name="berat" value="${hewan.berat}" min="0" step="0.1" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
          <select name="jenis_kelamin" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="jantan" ${hewan.jenis_kelamin === 'jantan' ? 'selected' : ''}>Jantan</option>
            <option value="betina" ${hewan.jenis_kelamin === 'betina' ? 'selected' : ''}>Betina</option>
            <option value="tidak diketahui" ${hewan.jenis_kelamin === 'tidak diketahui' ? 'selected' : ''}>Tidak Diketahui</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Khusus</label>
        <textarea name="kondisi_khusus" rows="2"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Alergi, kebiasaan khusus, dll.">${hewan.kondisi_khusus || ''}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Medis</label>
        <textarea name="catatan_medis" rows="2"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Riwayat penyakit, vaksinasi, dll.">${hewan.catatan_medis || ''}</textarea>
      </div>

      ${latestBooking ? `
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Penitipan Terakhir</label>
        <select name="status_penitipan" 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Pending</option>
          <option value="aktif" ${currentStatus === 'aktif' ? 'selected' : ''}>Aktif</option>
          <option value="selesai" ${currentStatus === 'selesai' ? 'selected' : ''}>Selesai</option>
          <option value="batal" ${currentStatus === 'batal' ? 'selected' : ''}>Batal</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Mengubah status ini akan mengupdate booking terakhir.</p>
      </div>
      ` : ''}
    </div>
  `;

  document.getElementById('editContent').innerHTML = content;
  document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
  const detailModal = document.getElementById('detailModal');
  const editModal = document.getElementById('editModal');
  
  if (event.target === detailModal) {
    closeDetailModal();
  }
  if (event.target === editModal) {
    closeEditModal();
  }
});

console.log('Operasional page script loaded.');
</script>