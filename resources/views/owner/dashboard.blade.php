@extends('owner.layouts.app')

@section('content')
<div class="space-y-6">
  {{-- Stats Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- Pendapatan Bulan Ini --}}
    <div class="bg-white shadow rounded-lg p-4">
      <div class="flex flex-row items-center justify-between">
        <h2 class="text-sm font-medium">Pendapatan Bulan Ini</h2>
        <i class="fas fa-dollar-sign text-gray-500"></i>
      </div>
      <div class="mt-2 text-2xl font-bold">Rp 67.000.000</div>
      <p class="text-xs text-gray-500"><span class="text-green-600">+15.2%</span> dari bulan lalu</p>
    </div>

    {{-- Hewan Aktif --}}
    <div class="bg-white shadow rounded-lg p-4">
      <div class="flex flex-row items-center justify-between">
        <h2 class="text-sm font-medium">Hewan Aktif</h2>
        <i class="fas fa-paw text-gray-500"></i>
      </div>
      <div class="mt-2 text-2xl font-bold">127</div>
      <p class="text-xs text-gray-500"><span class="text-green-600">+8</span> hewan baru minggu ini</p>
    </div>

    {{-- Reservasi Bulan Ini --}}
    <div class="bg-white shadow rounded-lg p-4">
      <div class="flex flex-row items-center justify-between">
        <h2 class="text-sm font-medium">Reservasi Bulan Ini</h2>
        <i class="fas fa-calendar text-gray-500"></i>
      </div>
      <div class="mt-2 text-2xl font-bold">284</div>
      <p class="text-xs text-gray-500"><span class="text-green-600">+12.5%</span> dari bulan lalu</p>
    </div>

    {{-- Rating --}}
    <div class="bg-white shadow rounded-lg p-4">
      <div class="flex flex-row items-center justify-between">
        <h2 class="text-sm font-medium">Rating Rata-rata</h2>
        <i class="fas fa-star text-gray-500"></i>
      </div>
      <div class="mt-2 text-2xl font-bold">4.8</div>
      <p class="text-xs text-gray-500">Dari 156 ulasan bulan ini</p>
    </div>
  </div>

  {{-- Chart Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <!-- Tren Pendapatan -->
  <div class="bg-white shadow rounded-lg p-4 flex flex-col">
    <h2 class="text-sm font-medium mb-4">Tren Pendapatan</h2>
    <div class="flex-grow flex justify-center items-center">
      <canvas id="revenueChart" class="w-full h-80"></canvas>
    </div>
  </div>

  <!-- Distribusi Layanan -->
  <div class="bg-white shadow rounded-lg p-4 flex flex-col">
    <h2 class="text-sm font-medium mb-4">Distribusi Layanan</h2>
    <div class="flex-grow flex justify-center items-center">
      <canvas id="serviceChart" class="w-80 h-80"></canvas>
    </div>
  </div>
</div>

  {{-- Reservasi Terbaru & Notifikasi --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white shadow rounded-lg p-4">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-medium">Reservasi Terbaru</h2>
        <a href="#" class="px-2 py-1 text-xs border rounded">Lihat Semua</a>
      </div>
      <div class="space-y-3">
        <div class="flex items-center justify-between p-3 border rounded-lg">
          <div>
            <p class="font-medium">Sarah Johnson</p>
            <p class="text-sm text-gray-500">Buddy (Golden Retriever)</p>
            <p class="text-xs text-gray-400">Check-in: 2024-01-15</p>
          </div>
          <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Dikonfirmasi</span>
        </div>
        <div class="flex items-center justify-between p-3 border rounded-lg">
          <div>
            <p class="font-medium">Michael Chen</p>
            <p class="text-sm text-gray-500">Whiskers (Persian Cat)</p>
            <p class="text-xs text-gray-400">Check-in: 2024-01-16</p>
          </div>
          <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Menunggu</span>
        </div>
      </div>
    </div>

    {{-- Notifikasi --}}
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-sm font-medium mb-4">Notifikasi & Peringatan</h2>
      <div class="space-y-3">
        <div class="p-3 border bg-yellow-50 rounded-lg">
          <p class="font-medium text-yellow-800">Perawatan Kandang B-12</p>
          <p class="text-sm text-yellow-700">Kandang perlu pembersihan mendalam hari ini</p>
          <p class="text-xs text-yellow-600">2 jam yang lalu</p>
        </div>
        <div class="p-3 border bg-blue-50 rounded-lg">
          <p class="font-medium text-blue-800">Vaksinasi Jatuh Tempo</p>
          <p class="text-sm text-blue-700">3 hewan perlu vaksinasi minggu ini</p>
          <p class="text-xs text-blue-600">5 jam yang lalu</p>
        </div>
        <div class="p-3 border bg-green-50 rounded-lg">
          <p class="font-medium text-green-800">Target Bulanan Tercapai</p>
          <p class="text-sm text-green-700">Pendapatan bulan ini telah mencapai target</p>
          <p class="text-xs text-green-600">1 hari yang lalu</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctxRevenue = document.getElementById('revenueChart');
  new Chart(ctxRevenue, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Pendapatan',
        data: [45000000, 52000000, 48000000, 61000000, 58000000, 67000000],
        borderColor: '#8884d8',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: {
          left: 10,
          right: 10,
          top: 10,
          bottom: 10
        }
      }
    }
  });

  const ctxService = document.getElementById('serviceChart');
  new Chart(ctxService, {
    type: 'pie',
    data: {
      labels: ['Penitipan', 'Grooming', 'Training', 'Konsultasi'],
      datasets: [{
        data: [60, 25, 10, 5],
        backgroundColor: ['#8884d8', '#82ca9d', '#ffc658', '#ff7300']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 12,
            padding: 15
          }
        }
      }
    }
  });
</script>
@endpush