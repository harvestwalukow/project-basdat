@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-bold border-b pb-4 mb-6">DASHBOARD</h1>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Penitipan Aktif</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">12</p>
  </div>
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Total Hewan</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">26</p>
  </div>
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Total Pengguna</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">45</p>
  </div>
</div>

<!-- Main Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Left Section -->
  <div class="lg:col-span-2 flex flex-col gap-8">
    <!-- Chart -->
    <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm h-[380px]">
      <h3 class="text-xl font-semibold mb-4 text-slate-800">Pendapatan Mingguan</h3>
      <div class="h-[300px]">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>

    <!-- Update Kondisi -->
    <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm h-[380px] flex flex-col">
      <h3 class="text-xl font-semibold mb-4 text-slate-800">Update Kondisi Terbaru</h3>
      <ul class="space-y-3 flex-1 overflow-y-auto pr-2">
        <li class="p-3 bg-blue-50 text-slate-700 rounded-md">
          Update kondisi: Buddy (Golden Retriever) dalam kondisi sehat.
        </li>
        <li class="p-3 bg-green-50 text-slate-700 rounded-md">
          Aktivitas terkini: Milo (Persian) baru saja check-in.
        </li>
        <li class="p-3 bg-yellow-50 text-slate-700 rounded-md">
          Catatan staff: Leo (Beagle) memerlukan perhatian khusus.
        </li>
      </ul>
    </div>
  </div>

  <!-- Right Section -->
  <div class="flex flex-col gap-8">
    <!-- Jadwal Hari Ini -->
    <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm h-[380px]">
      <h3 class="text-xl font-semibold mb-4 text-slate-800">Jadwal Hari Ini</h3>
      <div class="space-y-4">
        <div class="flex items-center p-3 bg-red-50 border-l-4 border-red-400 rounded-md">
          <span class="text-sm font-semibold mr-4 text-slate-700">09:00</span>
          <div class="flex-1">
            <p class="font-medium text-slate-800">Check-in: Buddy (Golden Retriever)</p>
          </div>
          <span class="text-xs font-bold text-red-700 bg-red-200 px-2 py-1 rounded-full">Menunggu</span>
        </div>
        <div class="flex items-center p-3 bg-green-50 border-l-4 border-green-400 rounded-md">
          <span class="text-sm font-semibold mr-4 text-slate-700">11:00</span>
          <div class="flex-1">
            <p class="font-medium text-slate-800">Grooming: Luna (Siberian)</p>
          </div>
          <span class="text-xs font-bold text-green-700 bg-green-200 px-2 py-1 rounded-full">Selesai</span>
        </div>
      </div>
    </div>

    <!-- Notifikasi & Peringatan -->
    <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm h-[380px] flex flex-col">
      <h3 class="text-xl font-semibold mb-4 text-slate-800">Notifikasi & Peringatan</h3>
      <div class="flex-1 overflow-y-auto pr-2 space-y-4">
        <div class="p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
          <label class="flex items-start gap-2 cursor-pointer">
            <input type="checkbox" class="mt-1 accent-amber-500" />
            <div>
              <p class="font-semibold text-amber-800">Perawatan Kandang B-12</p>
              <p class="text-sm text-amber-700">Kandang perlu pembersihan mendalam hari ini</p>
              <p class="text-xs text-amber-600 mt-1">2 jam yang lalu</p>
            </div>
          </label>
        </div>

        <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
          <label class="flex items-start gap-2 cursor-pointer">
            <input type="checkbox" class="mt-1 accent-blue-500" />
            <div>
              <p class="font-semibold text-blue-800">Vaksinasi Jatuh Tempo</p>
              <p class="text-sm text-blue-700">3 hewan perlu vaksinasi minggu ini</p>
              <p class="text-xs text-blue-600 mt-1">5 jam yang lalu</p>
            </div>
          </label>
        </div>

        <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
          <label class="flex items-start gap-2 cursor-pointer">
            <input type="checkbox" class="mt-1 accent-green-500" checked />
            <div>
              <p class="font-semibold text-green-800">Target Bulanan Tercapai</p>
              <p class="text-sm text-green-700">Pendapatan bulan ini telah mencapai target</p>
              <p class="text-xs text-green-600 mt-1">1 hari yang lalu</p>
            </div>
          </label>
        </div>

        <div class="p-4 bg-orange-50 border-l-4 border-orange-400 rounded-lg">
          <label class="flex items-start gap-2 cursor-pointer">
            <input type="checkbox" class="mt-1 accent-orange-500" />
            <div>
              <p class="font-semibold text-orange-800">Stok Makanan Menipis</p>
              <p class="text-sm text-orange-700">Harap cek gudang untuk restock</p>
              <p class="text-xs text-orange-600 mt-1">3 hari yang lalu</p>
            </div>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'line',
  data: {
    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    datasets: [{
      label: 'Pendapatan (Rp)',
      data: [1200000, 1900000, 1500000, 2500000, 2200000, 3000000, 2800000],
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
