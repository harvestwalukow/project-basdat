@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-bold border-b pb-4 mb-6">DASHBOARD</h1>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold text-gray-600">Penitipan Aktif</h3>
    <p class="text-3xl font-bold mt-2">12</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold text-gray-600">Total Hewan</h3>
    <p class="text-3xl font-bold mt-2">26</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold text-gray-600">Total Pengguna</h3>
    <p class="text-3xl font-bold mt-2">45</p>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Main Area (Charts & Notifications) -->
  <div class="lg:col-span-2 space-y-8">
    <!-- Weekly Revenue Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Pendapatan Mingguan</h3>
      <canvas id="revenueChart"></canvas>
    </div>

    <!-- Recent Updates -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Update Kondisi Terbaru</h3>
      <ul class="space-y-3">
        <li class="p-3 bg-blue-50 rounded-md">Update kondisi: Buddy (Golden Retriever) dalam kondisi sehat.</li>
        <li class="p-3 bg-green-50 rounded-md">Aktivitas terkini: Milo (Persian) baru saja check-in.</li>
        <li class="p-3 bg-yellow-50 rounded-md">Catatan staff: Leo (Beagle) memerlukan perhatian khusus.</li>
      </ul>
    </div>
  </div>

  <!-- Right Sidebar (Schedule & Service Distribution) -->
  <div class="space-y-8">
    <!-- Today's Schedule -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Jadwal Hari Ini</h3>
      <div class="space-y-4">
        <div class="flex items-center p-3 bg-red-50 border-l-4 border-red-400 rounded-md">
          <span class="text-sm font-semibold mr-4">09:00</span>
          <div class="flex-1">
            <p class="font-medium">Check-in: Buddy (Golden Retriever)</p>
          </div>
          <span class="text-xs font-bold text-red-600 bg-red-200 px-2 py-1 rounded-full">Menunggu</span>
        </div>
          <div class="flex items-center p-3 bg-green-50 border-l-4 border-green-400 rounded-md">
          <span class="text-sm font-semibold mr-4">11:00</span>
          <div class="flex-1">
            <p class="font-medium">Grooming: Luna (Siberian)</p>
          </div>
          <span class="text-xs font-bold text-green-600 bg-green-200 px-2 py-1 rounded-full">Selesai</span>
        </div>
      </div>
    </div>

    <!-- Service Distribution Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Distribusi Paket Layanan</h3>
      <p class="text-center text-sm text-gray-500 mb-4">Presentasi penitipan setiap paket</p>
      <div class="relative w-48 h-48 mx-auto">
        <canvas id="servicesChart"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
// Weekly Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'line',
  data: {
    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    datasets: [{
      label: 'Pendapatan (Rp)',
      data: [1200000, 1900000, 1500000, 2500000, 2200000, 3000000, 2800000],
      borderColor: 'rgba(242, 120, 75, 1)',
      backgroundColor: 'rgba(242, 120, 75, 0.2)',
      borderWidth: 2,
      tension: 0.4,
      fill: true,
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

// Services Distribution Chart
const servicesCtx = document.getElementById('servicesChart').getContext('2d');
new Chart(servicesCtx, {
  type: 'pie',
  data: {
    labels: ['Paket Basic', 'Paket Premium', 'Paket Deluxe'],
    datasets: [{
      label: 'Distribusi Paket',
      data: [45, 35, 20],
      backgroundColor: [
        'rgba(255, 206, 86, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 99, 132, 0.8)'
      ],
      borderColor: '#fff',
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom',
      }
    }
  }
});
@endpush
