@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Laporan & Analytics</h1>
      <p class="text-gray-500">Analisis performa bisnis dan insights mendalam</p>
    </div>
    <div class="flex gap-2">
      <button class="flex items-center gap-2 px-3 py-2 border rounded-lg text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/></svg>
        Email Report
      </button>
      <button class="flex items-center gap-2 px-3 py-2 border rounded-lg text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 9h12M6 13h12M6 17h12"/></svg>
        Print
      </button>
      <button class="flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Export PDF
      </button>
    </div>
  </div>

  <!-- Report Controls -->
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="flex flex-col md:flex-row gap-4">
      <select id="reportType" class="w-48 border rounded-lg p-2" onchange="filterReport()">
        <option value="executive">Executive Summary</option>
        <option value="financial">Financial Report</option>
        <option value="operational">Operational Report</option>
        <option value="customer">Customer Analytics</option>
      </select>
      <select id="timeRange" class="w-48 border rounded-lg p-2" onchange="filterReport()">
        <option value="today">Hari Ini</option>
        <option value="week">Minggu Ini</option>
        <option value="month" selected>Bulan Ini</option>
        <option value="3months">3 Bulan Terakhir</option>
        <option value="6months">6 Bulan Terakhir</option>
        <option value="year">Tahun Ini</option>
      </select>
      <button onclick="exportPDF()" class="ml-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        <i class="fas fa-download mr-2"></i>Export PDF
      </button>
    </div>
  </div>

  <!-- Tabs -->
  <div class="space-y-4">
    <div class="flex gap-2 border-b">
      <button class="report-tab px-4 py-2 border-b-2 border-blue-600 font-medium text-blue-600" data-tab="summary">Executive Summary</button>
      <button class="report-tab px-4 py-2 text-gray-500 hover:text-gray-700" data-tab="revenue">Revenue Analysis</button>
      <button class="report-tab px-4 py-2 text-gray-500 hover:text-gray-700" data-tab="customer">Customer Insights</button>
      <button class="report-tab px-4 py-2 text-gray-500 hover:text-gray-700" data-tab="operational">Operational KPIs</button>
    </div>

    <!-- Executive Summary Tab -->
    <div id="summary-tab" class="tab-content space-y-4">
      <!-- Key Metrics -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format(($totalRevenue ?? 0) / 1000000, 0) }}M</p>
          </div>
          <span class="text-green-600 text-sm">{{ $revenueGrowth ?? '0%' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Bookings</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalBookings ?? 0 }}</p>
          </div>
          <span class="text-green-600 text-sm">{{ $bookingsGrowth ?? '0%' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Active Customers</p>
            <p class="text-2xl font-bold text-purple-600">{{ $activeCustomers ?? 0 }}</p>
          </div>
          <span class="text-green-600 text-sm">{{ $customersGrowth ?? '0%' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Avg Rating</p>
            <p class="text-2xl font-bold text-orange-600">{{ number_format($avgRating ?? 0, 1) }}</p>
          </div>
          <span class="text-green-600 text-sm">{{ $ratingChange ?? '0' }}</span>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-xl shadow h-64">
          <canvas id="revenueChart" class="h-full w-full"></canvas>
        </div>
        <div class="bg-white p-4 rounded-xl shadow h-64">
          <canvas id="bookingChart" class="h-full w-full"></canvas>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="px-4 py-2 text-left">Layanan</th>
              <th class="px-4 py-2 text-left">Revenue</th>
              <th class="px-4 py-2 text-left">Bookings</th>
              <th class="px-4 py-2 text-left">Rating</th>
              <th class="px-4 py-2 text-left">Growth</th>
            </tr>
          </thead>
          <tbody>
            @forelse($servicePerformance ?? [] as $service)
              <tr class="border-t">
                <td class="px-4 py-2">{{ $service->name }}</td>
                <td class="px-4 py-2">Rp {{ number_format($service->revenue / 1000000, 1) }}M</td>
                <td class="px-4 py-2">{{ $service->bookings }}</td>
                <td class="px-4 py-2">{{ number_format($service->rating, 1) }} ★</td>
                <td class="px-4 py-2 text-green-600">{{ $service->growth }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data performa layanan</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Revenue Analysis Tab -->
    <div id="revenue-tab" class="tab-content hidden space-y-4">
      <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold mb-4">Analisis Pendapatan Bulanan</h3>
        <canvas id="revenueDetailChart" class="h-64"></canvas>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-6 rounded-xl shadow">
          <h3 class="text-lg font-semibold mb-4">Top Revenue Sources</h3>
          <div class="space-y-3">
            @forelse($servicePerformance ?? [] as $service)
              <div class="flex items-center justify-between">
                <span class="text-sm">{{ $service->name }}</span>
                <span class="font-bold text-green-600">Rp {{ number_format($service->revenue / 1000000, 1) }}M</span>
              </div>
            @empty
              <p class="text-gray-500 text-center">Belum ada data</p>
            @endforelse
          </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow">
          <h3 class="text-lg font-semibold mb-4">Revenue Growth Trend</h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm">Pertumbuhan Bulan Ini</span>
              <span class="text-green-600 font-bold">{{ $revenueGrowth ?? '0%' }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm">Total Revenue YTD</span>
              <span class="font-bold">Rp {{ number_format(($totalRevenue ?? 0) / 1000000, 1) }}M</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Insights Tab -->
    <div id="customer-tab" class="tab-content hidden space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-xl shadow text-center">
          <p class="text-sm text-gray-500">Total Customers</p>
          <p class="text-3xl font-bold text-purple-600">{{ $activeCustomers ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center">
          <p class="text-sm text-gray-500">New Customers</p>
          <p class="text-3xl font-bold text-blue-600">{{ $customersGrowth ?? '0' }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center">
          <p class="text-sm text-gray-500">Customer Satisfaction</p>
          <p class="text-3xl font-bold text-orange-600">{{ number_format($avgRating ?? 0, 1) }}/5.0</p>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold mb-4">Customer Booking Patterns</h3>
        <canvas id="customerChart" class="h-64"></canvas>
      </div>
    </div>

    <!-- Operational KPIs Tab -->
    <div id="operational-tab" class="tab-content hidden space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-xl shadow text-center">
          <p class="text-sm text-gray-500">Total Bookings</p>
          <p class="text-3xl font-bold text-blue-600">{{ $totalBookings ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center">
          <p class="text-sm text-gray-500">Active Penitipan</p>
          <p class="text-3xl font-bold text-green-600">{{ \App\Models\Penitipan::where('status', 'aktif')->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center">
          <p class="text-sm text-gray-500">Total Pets</p>
          <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Hewan::count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center">
          <p class="text-sm text-gray-500">Staff Active</p>
          <p class="text-3xl font-bold text-orange-600">{{ \App\Models\Pengguna::whereIn('role', ['admin', 'staff'])->count() }}</p>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold mb-4">Occupancy Rate</h3>
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span>Current Occupancy</span>
            <span class="font-bold">{{ \App\Models\Penitipan::where('status', 'aktif')->count() }} / 50 rooms</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-blue-600 h-4 rounded-full" style="width: {{ min((\App\Models\Penitipan::where('status', 'aktif')->count() / 50) * 100, 100) }}%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  // Tab switching functionality
  document.querySelectorAll('.report-tab').forEach(button => {
    button.addEventListener('click', function() {
      const targetTab = this.getAttribute('data-tab');
      
      // Update button states
      document.querySelectorAll('.report-tab').forEach(btn => {
        btn.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600', 'font-medium');
        btn.classList.add('text-gray-500');
      });
      this.classList.add('border-b-2', 'border-blue-600', 'text-blue-600', 'font-medium');
      this.classList.remove('text-gray-500');
      
      // Update tab content visibility
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
      });
      document.getElementById(targetTab + '-tab').classList.remove('hidden');
    });
  });

  // Revenue Trend (Line Chart) - Executive Summary
  const revenueData = @json($revenueChartData ?? ['labels' => [], 'data' => []]);
  
  const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctxRevenue, {
    type: 'line',
    data: {
      labels: revenueData.labels,
      datasets: [{
        label: 'Revenue (Juta Rupiah)',
        data: revenueData.data,
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true },
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Revenue: Rp ' + context.parsed.y.toFixed(2) + 'M';
            }
          }
        }
      },
      scales: {
        y: { 
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value + 'M';
            }
          }
        }
      }
    }
  });

  // Booking vs Customer (Bar Chart) - Executive Summary
  const bookingData = @json($bookingChartData ?? ['labels' => [], 'bookings' => [], 'customers' => []]);
  
  const ctxBooking = document.getElementById('bookingChart').getContext('2d');
  const bookingChart = new Chart(ctxBooking, {
    type: 'bar',
    data: {
      labels: bookingData.labels,
      datasets: [
        { 
          label: 'Bookings', 
          data: bookingData.bookings, 
          backgroundColor: 'rgba(59, 130, 246, 0.8)',
          borderColor: '#3b82f6',
          borderWidth: 1
        },
        { 
          label: 'Active Customers', 
          data: bookingData.customers, 
          backgroundColor: 'rgba(168, 85, 247, 0.8)',
          borderColor: '#a855f7',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true },
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Revenue Detail Chart - Revenue Tab
  const ctxRevenueDetail = document.getElementById('revenueDetailChart').getContext('2d');
  const revenueDetailChart = new Chart(ctxRevenueDetail, {
    type: 'line',
    data: {
      labels: revenueData.labels,
      datasets: [{
        label: 'Monthly Revenue',
        data: revenueData.data,
        backgroundColor: 'rgba(34, 197, 94, 0.2)',
        borderColor: 'rgba(34, 197, 94, 1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true },
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Revenue: Rp ' + context.parsed.y.toFixed(2) + 'M';
            }
          }
        }
      },
      scales: {
        y: { 
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value + 'M';
            }
          }
        }
      }
    }
  });

  // Customer Chart - Customer Tab
  const ctxCustomer = document.getElementById('customerChart').getContext('2d');
  const customerChart = new Chart(ctxCustomer, {
    type: 'line',
    data: {
      labels: bookingData.labels,
      datasets: [{
        label: 'Active Customers',
        data: bookingData.customers,
        backgroundColor: 'rgba(168, 85, 247, 0.2)',
        borderColor: 'rgba(168, 85, 247, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true },
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Filter Report Function
  function filterReport() {
    const reportType = document.getElementById('reportType').value;
    const timeRange = document.getElementById('timeRange').value;
    console.log('Filtering report:', reportType, timeRange);
    // Implement AJAX call to reload data based on filters
    // For now, just log the values
    alert('Filter akan diterapkan: ' + reportType + ' - ' + timeRange);
  }

  // Export PDF Function
  function exportPDF() {
    alert('Fitur export PDF akan segera tersedia');
    // Implement PDF export functionality
  }

  // Print Function
  function printReport() {
    window.print();
  }
@endpush

