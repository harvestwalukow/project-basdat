@extends('admin.layouts.app')

@section('content')
<div class="pb-8">
  <!-- Success/Error Messages -->
  @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
      <span>{{ session('success') }}</span>
      <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center justify-between">
      <span>{{ session('error') }}</span>
      <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  @endif

  <!-- Header -->
  <header class="mb-6">
    <div>
      <h1 class="text-3xl font-bold">PEMBAYARAN</h1>
      <p class="text-gray-600">Lacak semua pembayaran dan transaksi</p>
    </div>
  </header>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Pendapatan</h3>
        <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Pembayaran</h3>
        <p class="text-3xl font-bold mt-2">{{ $totalPembayaran }}</p>
    </div>
  </div>

  <!-- Charts -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Metode Pembayaran</h3>
      <div class="h-64">
        <canvas id="paymentMethodChart"></canvas>
      </div>
    </div>
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Pendapatan 7 Hari Terakhir</h3>
      <div class="h-64">
        <canvas id="dailyRevenueChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4 p-4 bg-white rounded-lg shadow-md">
      <input type="text" id="paymentSearch" placeholder="Cari ID, pelanggan..." 
        class="flex-grow min-w-[200px] px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        onkeyup="filterPayments()">
      <select id="metodeFilter" class="px-4 py-2 border rounded-lg" onchange="filterPayments()">
        <option value="">Semua Metode</option>
        <option value="cash">Cash</option>
        <option value="transfer">Transfer Bank</option>
        <option value="e_wallet">E-Wallet</option>
        <option value="qris">QRIS</option>
        <option value="kartu_kredit">Kartu Kredit</option>
      </select>
      <select id="statusFilter" class="px-4 py-2 border rounded-lg" onchange="filterPayments()">
        <option value="">Semua Status</option>
        <option value="lunas">Lunas</option>
        <option value="gagal">Gagal</option>
      </select>
      <input type="date" id="dateFilter" class="px-4 py-2 border rounded-lg" onchange="filterPayments()">
    </div>
  </div>

  <!-- Payments Table -->
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="font-semibold text-lg">DAFTAR PEMBAYARAN</h3>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
      <div class="overflow-y-auto scrollbar-custom" style="max-height: 600px;">
        <table class="w-full min-w-max">
          <thead class="bg-gray-50 text-left text-sm text-gray-600 sticky top-0 z-10">
            <tr>
              <th class="p-4 font-semibold">ID Pembayaran</th>
              <th class="p-4 font-semibold">ID Penitipan</th>
              <th class="p-4 font-semibold">Pelanggan</th>
              <th class="p-4 font-semibold">Tanggal Bayar</th>
              <th class="p-4 font-semibold">Jumlah</th>
              <th class="p-4 font-semibold">Metode</th>
              <th class="p-4 font-semibold">Status</th>
              <th class="p-4 font-semibold">Aksi</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($pembayarans as $pembayaran)
              <tr class="payment-row border-b hover:bg-gray-50"
                  data-metode="{{ $pembayaran->metode_pembayaran }}"
                  data-status="{{ $pembayaran->status_pembayaran }}"
                  data-date="{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('Y-m-d') : '' }}">
                <td class="p-4 font-medium">{{ $pembayaran->nomor_transaksi }}</td>
                <td class="p-4">PNT-{{ str_pad($pembayaran->id_penitipan, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="p-4">
                  <div>
                    <p class="font-medium">{{ $pembayaran->penitipan->pemilik->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ $pembayaran->penitipan->pemilik->email }}</p>
                  </div>
                </td>
                <td class="p-4">
                  @if($pembayaran->tanggal_bayar)
                    {{ $pembayaran->tanggal_bayar->format('d M Y') }}
                  @else
                    <span class="text-gray-400">-</span>
                  @endif
                </td>
                <td class="p-4 font-semibold">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                <td class="p-4">
                  <span class="px-2 py-1 text-xs rounded-full
                    @if($pembayaran->metode_pembayaran == 'cash') bg-gray-100 text-gray-700
                    @elseif($pembayaran->metode_pembayaran == 'transfer') bg-blue-100 text-blue-700
                    @elseif($pembayaran->metode_pembayaran == 'e_wallet') bg-green-100 text-green-700
                    @elseif($pembayaran->metode_pembayaran == 'qris') bg-yellow-100 text-yellow-700
                    @else bg-purple-100 text-purple-700
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}
                  </span>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 text-xs font-semibold rounded-full
                    @if($pembayaran->status_pembayaran == 'lunas') bg-green-100 text-green-700
                    @elseif($pembayaran->status_pembayaran == 'pending') bg-yellow-100 text-yellow-700
                    @else bg-red-100 text-red-700
                    @endif">
                    {{ ucfirst($pembayaran->status_pembayaran) }}
                  </span>
                </td>
                <td class="p-4">
                  @if($pembayaran->status_pembayaran == 'pending')
                    <button 
                      onclick="openPaymentModal({{ $pembayaran->id_pembayaran }}, '{{ $pembayaran->nomor_transaksi }}', {{ $pembayaran->jumlah_bayar }}, '{{ $pembayaran->metode_pembayaran }}')"
                      class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                      Konfirmasi Bayar
                    </button>
                  @elseif($pembayaran->status_pembayaran == 'lunas')
                    <span class="text-xs text-gray-500">-</span>
                  @else
                    <button 
                      onclick="openPaymentModal({{ $pembayaran->id_pembayaran }}, '{{ $pembayaran->nomor_transaksi }}', {{ $pembayaran->jumlah_bayar }}, '{{ $pembayaran->metode_pembayaran }}')"
                      class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                      Update Status
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="p-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="text-lg font-medium">Belum ada data pembayaran</p>
                  <p class="text-sm text-gray-400 mt-1">Data pembayaran akan muncul di sini</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="p-8 text-center text-gray-500 hidden">
      <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <p class="text-lg font-medium">Tidak ada hasil ditemukan</p>
      <p class="text-sm text-gray-400 mt-1">Coba ubah filter pencarian Anda</p>
    </div>
  </div>
</div>

<!-- Modal Update Pembayaran -->
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-bold text-gray-800">Update Status Pembayaran</h3>
      <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <form id="paymentForm" method="POST" action="">
      @csrf
      @method('PUT')
      
      <div class="space-y-4 mb-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Transaksi</label>
          <p id="modalNomorTransaksi" class="text-gray-900 font-semibold">-</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
          <p id="modalJumlah" class="text-gray-900 font-semibold">-</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
          <select name="metode_pembayaran" id="modalMetode" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="cash">Cash</option>
            <option value="transfer">Transfer Bank</option>
            <option value="e_wallet">E-Wallet</option>
            <option value="qris">QRIS</option>
            <option value="kartu_kredit">Kartu Kredit</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
          <select name="status_pembayaran" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="pending">Pending</option>
            <option value="lunas">Lunas</option>
            <option value="gagal">Gagal</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembayaran</label>
          <input type="datetime-local" name="tanggal_bayar" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan waktu sekarang</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
          <textarea name="catatan" rows="2"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Tambahkan catatan jika diperlukan"></textarea>
        </div>
      </div>

      <div class="flex gap-3">
        <button type="button" onclick="closePaymentModal()"
          class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
          Batal
        </button>
        <button type="submit"
          class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
          Update Status
        </button>
      </div>
    </form>
  </div>
</div>

<style>
/* Custom scrollbar styling */
.scrollbar-custom::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.scrollbar-custom::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.scrollbar-custom::-webkit-scrollbar-thumb {
  background: #94a3b8;
  border-radius: 4px;
}

.scrollbar-custom::-webkit-scrollbar-thumb:hover {
  background: #64748b;
}

/* For Firefox */
.scrollbar-custom {
  scrollbar-width: thin;
  scrollbar-color: #94a3b8 #f1f5f9;
}

/* Ensure sticky header works properly */
thead th {
  background-color: #f9fafb;
}
</style>

<script>
// Filter function
function filterPayments() {
  const searchValue = document.getElementById('paymentSearch').value.toLowerCase();
  const metodeValue = document.getElementById('metodeFilter').value.toLowerCase();
  const statusValue = document.getElementById('statusFilter').value.toLowerCase();
  const dateValue = document.getElementById('dateFilter').value;

  const rows = document.getElementsByClassName('payment-row');
  let visibleCount = 0;

  for (let i = 0; i < rows.length; i++) {
    const row = rows[i];
    const rowText = row.innerText.toLowerCase();
    const rowMetode = row.getAttribute('data-metode') || '';
    const rowStatus = row.getAttribute('data-status') || '';
    const rowDate = row.getAttribute('data-date') || '';

    let showRow = true;

    if (searchValue && !rowText.includes(searchValue)) showRow = false;
    if (metodeValue && rowMetode !== metodeValue) showRow = false;
    if (statusValue && rowStatus !== statusValue) showRow = false;
    if (dateValue && rowDate !== dateValue) showRow = false;

    row.style.display = showRow ? '' : 'none';
    if (showRow) visibleCount++;
  }

  const noResults = document.getElementById('noResults');
  if (noResults) {
    noResults.classList.toggle('hidden', visibleCount > 0);
  }
}

// Modal functions
function openPaymentModal(idPembayaran, nomorTransaksi, jumlah, metode) {
  const modal = document.getElementById('paymentModal');
  const form = document.getElementById('paymentForm');
  
  // Set form action URL
  form.action = '/admin/pembayaran/' + idPembayaran + '/update-status';
  
  // Fill modal data
  document.getElementById('modalNomorTransaksi').textContent = nomorTransaksi;
  document.getElementById('modalJumlah').textContent = 'Rp ' + parseInt(jumlah).toLocaleString('id-ID');
  document.getElementById('modalMetode').value = metode;
  
  // Show modal
  modal.classList.remove('hidden');
}

function closePaymentModal() {
  const modal = document.getElementById('paymentModal');
  modal.classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
  const modal = document.getElementById('paymentModal');
  if (event.target === modal) {
    closePaymentModal();
  }
});

console.log('Pembayaran page loaded');
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Payment Method Chart (Horizontal Bar)
  const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
  new Chart(paymentMethodCtx, {
    type: 'bar',
    data: {
      labels: ['Cash', 'Transfer Bank', 'E-Wallet', 'QRIS', 'Kartu Kredit'],
      datasets: [{
        label: 'Jumlah Transaksi',
        data: [
          {{ $paymentMethodData['cash'] ?? 0 }},
          {{ $paymentMethodData['transfer'] }},
          {{ $paymentMethodData['e_wallet'] }},
          {{ $paymentMethodData['qris'] }},
          {{ $paymentMethodData['kartu_kredit'] ?? 0 }}
        ],
        backgroundColor: [
          'rgba(107, 114, 128, 0.8)',
          'rgba(59, 130, 246, 0.8)',
          'rgba(16, 185, 129, 0.8)',
          'rgba(245, 158, 11, 0.8)',
          'rgba(239, 68, 68, 0.8)'
        ],
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: { 
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.parsed.x + ' transaksi';
            }
          }
        }
      },
      scales: {
        x: { 
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });

  // Daily Revenue Chart (Line/Area)
  const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
  new Chart(dailyRevenueCtx, {
    type: 'line',
    data: {
      labels: @json($dailyRevenueLabels),
      datasets: [{
        label: 'Pendapatan',
        data: @json($dailyRevenueData),
        borderColor: 'rgba(59, 130, 246, 1)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        borderWidth: 3,
        tension: 0.4,
        fill: true,
        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
            }
          }
        }
      },
      scales: {
        y: { 
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              if (value >= 1000000) {
                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
              } else if (value >= 1000) {
                return 'Rp ' + (value / 1000) + 'rb';
              }
              return 'Rp ' + value;
            }
          }
        }
      }
    }
  });
</script>
@endpush