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
            <p class="text-2xl font-bold text-green-600">Rp 91M</p>
          </div>
          <span class="text-green-600 text-sm">+15.2%</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Bookings</p>
            <p class="text-2xl font-bold text-blue-600">284</p>
          </div>
          <span class="text-green-600 text-sm">+12.5%</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Active Customers</p>
            <p class="text-2xl font-bold text-purple-600">212</p>
          </div>
          <span class="text-green-600 text-sm">+8.7%</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Avg Rating</p>
            <p class="text-2xl font-bold text-orange-600">4.8</p>
          </div>
          <span class="text-green-600 text-sm">+0.2</span>
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
            <tr class="border-t">
              <td class="px-4 py-2">Penitipan Premium</td>
              <td class="px-4 py-2">Rp 54.6M</td>
              <td class="px-4 py-2">218</td>
              <td class="px-4 py-2">4.9 ⭐</td>
              <td class="px-4 py-2 text-green-600">+15.2%</td>
            </tr>
            <tr class="border-t">
              <td class="px-4 py-2">Penitipan Standard</td>
              <td class="px-4 py-2">Rp 32.4M</td>
              <td class="px-4 py-2">216</td>
              <td class="px-4 py-2">4.7 ⭐</td>
              <td class="px-4 py-2 text-green-600">+8.5%</td>
            </tr>
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
  const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctxRevenue, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
      datasets: [{
        label: 'Revenue (Miliar)',
        data: [50, 60, 70, 65, 80, 91],
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true },
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Booking vs Customer (Bar Chart)
  const ctxBooking = document.getElementById('bookingChart').getContext('2d');
  const bookingChart = new Chart(ctxBooking, {
    type: 'bar',
    data: {
      labels: ['Premium', 'Standard', 'Economy'],
      datasets: [
        { label: 'Bookings', data: [218, 216, 120], backgroundColor: '#3b82f6' },
        { label: 'Active Customers', data: [212, 200, 100], backgroundColor: '#a855f7' }
      ]
    },
    options: {
      responsive: true,
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
