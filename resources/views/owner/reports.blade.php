@extends('owner.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Laporan & Analytics</h1>
      <p class="text-muted-foreground">Analisis performa bisnis dan insights mendalam</p>
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
      <select class="w-48 border rounded-lg p-2">
        <option>Executive Summary</option>
        <option>Financial Report</option>
        <option>Operational Report</option>
        <option>Customer Analytics</option>
      </select>
      <select class="w-48 border rounded-lg p-2">
        <option>Hari Ini</option>
        <option>Minggu Ini</option>
        <option selected>Bulan Ini</option>
        <option>3 Bulan Terakhir</option>
        <option>6 Bulan Terakhir</option>
        <option>Tahun Ini</option>
      </select>
    </div>
  </div>

  <!-- Tabs -->
  <div class="space-y-4">
    <div class="flex gap-2 border-b">
      <button class="px-4 py-2 border-b-2 border-blue-600 font-medium">Executive Summary</button>
      <button class="px-4 py-2 text-gray-500">Revenue Analysis</button>
      <button class="px-4 py-2 text-gray-500">Customer Insights</button>
      <button class="px-4 py-2 text-gray-500">Operational KPIs</button>
    </div>

    <!-- Executive Summary -->
    <div class="space-y-4">
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
  </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Script -->
<script>
  // Revenue Trend (Line Chart)
  const revenueData = @json($revenueChartData ?? ['labels' => [], 'data' => []]);
  
  const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctxRevenue, {
    type: 'line',
    data: {
      labels: revenueData.labels,
      datasets: [{
        label: 'Revenue (Juta)',
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
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Booking vs Customer (Bar Chart)
  const bookingData = @json($bookingChartData ?? ['labels' => [], 'bookings' => [], 'customers' => []]);
  
  const ctxBooking = document.getElementById('bookingChart').getContext('2d');
  const bookingChart = new Chart(ctxBooking, {
    type: 'bar',
    data: {
      labels: bookingData.labels,
      datasets: [
        { label: 'Bookings', data: bookingData.bookings, backgroundColor: '#3b82f6' },
        { label: 'Active Customers', data: bookingData.customers, backgroundColor: '#a855f7' }
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
</script>
@endsection