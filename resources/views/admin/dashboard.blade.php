@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-bold border-b pb-4 mb-6">DASHBOARD</h1>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Penitipan Aktif</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">0</p>
  </div>
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Total Hewan</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">0</p>
  </div>
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Total Pengguna</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">0</p>
  </div>
</div>

<!-- Atas: Chart & Jadwal -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
  <!-- Chart -->
  <div class="lg:col-span-2 bg-white border border-slate-100 p-6 rounded-2xl shadow-sm h-[380px]">
    <h3 class="text-xl font-semibold mb-4 text-slate-800">Pendapatan Mingguan</h3>
    <div class="h-[300px]">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

  <!-- Jadwal Hari Ini -->
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm h-[380px]">
    <h3 class="text-xl font-semibold mb-4 text-slate-800">Jadwal Hari Ini</h3>
    <div class="space-y-4">
      <!-- Jadwal akan muncul di sini -->
    </div>
  </div>
</div>

<!-- Bawah: Update Kondisi (full width) -->
<div class="grid grid-cols-1 lg:grid-cols-3">
  <div class="lg:col-span-3 bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-xl font-semibold mb-4 text-slate-800">Update Kondisi Terbaru</h3>

    <!-- Tinggi diperpanjang + scroll kalau isi memanjang -->
    <ul class="space-y-3 max-h-[520px] overflow-y-auto pr-2">
      <!-- Update kondisi akan muncul di sini -->
    </ul>
  </div>
</div>
@endsection

@push('scripts')
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'line',
  data: {
    labels: ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'],
    datasets: [{
      label: 'Pendapatan (Rp)',
      data: [0,0,0,0,0,0,0],
      borderColor: 'rgba(242,120,75,1)',
      backgroundColor: 'rgba(242,120,75,0.2)',
      pointRadius: 4,
      borderWidth: 2,
      tension: 0.4,
      fill: true,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: { y: { beginAtZero: true } },
    plugins: { legend: { display: true, labels: { boxWidth: 12 } } }
  }
});
@endpush