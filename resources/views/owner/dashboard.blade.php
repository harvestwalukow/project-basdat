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
<div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
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
        data: [0, 0, 0, 0, 0, 0],
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
      labels: ['Pick-up & Delivery', 'Grooming', 'Kolam Renang', 'Boarding'],
      datasets: [{
        data: [0, 0, 0, 0],
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