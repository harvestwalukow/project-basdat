@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Laporan & Analytics</h1>
      <p id="pageSubtitle" class="text-gray-500">Ringkasan performa bisnis bulan ini</p>
    </div>
  </div>

  <!-- Report Controls -->
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="flex items-center gap-4">
      <label for="timeRange" class="text-sm font-medium text-gray-700">Periode:</label>
      <select id="timeRange" class="w-48 border rounded-lg p-2" onchange="loadReportData(this.value)">
        <option value="month" selected>Bulan Ini</option>
        <option value="3months">3 Bulan Terakhir</option>
        <option value="6months">6 Bulan Terakhir</option>
        <option value="year">Tahun Ini</option>
      </select>
      <div id="loadingIndicator" class="hidden">
        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>
  </div>

  <!-- Executive Summary -->
  <div class="space-y-4">
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-6 rounded-xl shadow">
        <div class="flex items-center justify-between mb-2">
          <p class="text-sm text-gray-500">Total Pendapatan</p>
          <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <p id="totalRevenue" class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow">
        <div class="flex items-center justify-between mb-2">
          <p class="text-sm text-gray-500">Total Penitipan</p>
          <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
        </div>
        <p id="totalBookings" class="text-2xl font-bold text-gray-800">{{ $totalBookings ?? 0 }}</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow">
        <div class="flex items-center justify-between mb-2">
          <p class="text-sm text-gray-500">Pelanggan Aktif</p>
          <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <p id="activeCustomers" class="text-2xl font-bold text-gray-800">{{ $activeCustomers ?? 0 }}</p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded-xl shadow">
        <h3 id="revenueChartTitle" class="text-lg font-semibold mb-4">Tren Pendapatan</h3>
        <div class="h-64">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow">
        <h3 id="bookingChartTitle" class="text-lg font-semibold mb-4">Penitipan & Pelanggan</h3>
        <div class="h-64">
          <canvas id="bookingChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Service Performance Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Performa Layanan</h3>
        <p class="text-sm text-gray-500">Statistik layanan berdasarkan pendapatan dan jumlah pemesanan</p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesanan</th>
            </tr>
          </thead>
          <tbody id="servicePerformanceTable" class="bg-white divide-y divide-gray-200">
            @forelse($servicePerformance ?? [] as $service)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">Rp {{ number_format($service->revenue, 0, ',', '.') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ $service->bookings }} kali</div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <p class="mt-2">Belum ada data performa layanan periode ini</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Initialize charts
  let revenueChart = null;
  let bookingChart = null;

  // Format currency
  function formatCurrency(value) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
  }

  // Initialize Revenue Chart
  function initRevenueChart(data) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    if (revenueChart) {
      revenueChart.destroy();
    }
    
    revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Pendapatan (Juta Rupiah)',
          data: data.data,
          backgroundColor: 'rgba(34, 197, 94, 0.2)',
          borderColor: 'rgba(34, 197, 94, 1)',
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointRadius: 4,
          pointHoverRadius: 6,
          pointBackgroundColor: 'rgba(34, 197, 94, 1)',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            display: true,
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'Pendapatan: Rp ' + context.parsed.y.toFixed(2) + ' Juta';
              }
            }
          }
        },
        scales: {
          y: { 
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + value + 'Jt';
              }
            }
          }
        }
      }
    });
  }

  // Initialize Booking Chart
  function initBookingChart(data) {
    const ctx = document.getElementById('bookingChart').getContext('2d');
    
    if (bookingChart) {
      bookingChart.destroy();
    }
    
    bookingChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [
          { 
            label: 'Penitipan', 
            data: data.bookings, 
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 2,
            borderRadius: 4,
          },
          { 
            label: 'Pelanggan Aktif', 
            data: data.customers, 
            backgroundColor: 'rgba(168, 85, 247, 0.8)',
            borderColor: 'rgba(168, 85, 247, 1)',
            borderWidth: 2,
            borderRadius: 4,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            display: true,
            position: 'top',
          },
          tooltip: {
            mode: 'index',
            intersect: false,
          }
        },
        scales: {
          y: { 
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        }
      }
    });
  }

  // Get period title
  function getPeriodTitle(timeRange) {
    const titles = {
      'month': 'Bulan Ini',
      '3months': '3 Bulan Terakhir',
      '6months': '6 Bulan Terakhir',
      'year': 'Tahun Ini'
    };
    return titles[timeRange] || 'Bulan Ini';
  }

  // Load Report Data
  function loadReportData(timeRange) {
    const loadingIndicator = document.getElementById('loadingIndicator');
    loadingIndicator.classList.remove('hidden');

    fetch(`{{ route('admin.reports') }}?timeRange=${timeRange}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      // Update metrics
      document.getElementById('totalRevenue').textContent = formatCurrency(data.totalRevenue);
      document.getElementById('totalBookings').textContent = data.totalBookings;
      document.getElementById('activeCustomers').textContent = data.activeCustomers;

      // Update page subtitle
      const periodTitle = getPeriodTitle(timeRange);
      const subtitles = {
        'month': 'Ringkasan performa bisnis bulan ini',
        '3months': 'Ringkasan performa bisnis 3 bulan terakhir',
        '6months': 'Ringkasan performa bisnis 6 bulan terakhir',
        'year': 'Ringkasan performa bisnis tahun ini'
      };
      document.getElementById('pageSubtitle').textContent = subtitles[timeRange] || subtitles['month'];
      
      // Update chart titles
      document.getElementById('revenueChartTitle').textContent = `Tren Pendapatan (${periodTitle})`;
      document.getElementById('bookingChartTitle').textContent = `Penitipan & Pelanggan (${periodTitle})`;

      // Update charts
      initRevenueChart(data.revenueChartData);
      initBookingChart(data.bookingChartData);

      // Update table
      updateServiceTable(data.servicePerformance);

      loadingIndicator.classList.add('hidden');
    })
    .catch(error => {
      console.error('Error loading report data:', error);
      loadingIndicator.classList.add('hidden');
      alert('Gagal memuat data laporan. Silakan coba lagi.');
    });
  }

  // Update Service Performance Table
  function updateServiceTable(services) {
    const tbody = document.getElementById('servicePerformanceTable');
    
    if (services.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="3" class="px-6 py-8 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="mt-2">Belum ada data performa layanan periode ini</p>
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = services.map(service => `
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm font-medium text-gray-900">${service.name}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm text-gray-900">${formatCurrency(service.revenue)}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm text-gray-900">${service.bookings} kali</div>
        </td>
      </tr>
    `).join('');
  }

  // Initialize charts on page load
  document.addEventListener('DOMContentLoaded', function() {
    const revenueData = @json($revenueChartData ?? ['labels' => [], 'data' => []]);
    const bookingData = @json($bookingChartData ?? ['labels' => [], 'bookings' => [], 'customers' => []]);
    
    initRevenueChart(revenueData);
    initBookingChart(bookingData);
  });
</script>
@endpush

