@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-2xl font-bold text-slate-800">DASHBOARD</h1>
  <p class="text-slate-500 text-sm">Monitor business performance and daily operations.</p>
</div>

<!-- Row 1: KPI Revenue & Grafik Revenue Bulanan -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
  <!-- KPI Revenue (fact_keuangan_periodik) -->
  <div class="bg-white border border-slate-200 p-6 rounded-xl shadow-sm flex flex-col justify-between h-full relative overflow-hidden">
    <div>
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Revenue Bulan Ini</h3>
        <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </span>
      </div>
      <div class="mb-6">
        <p class="text-3xl font-bold text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-500 mt-1">Total pendapatan periode ini</p>
      </div>

      <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
        <div>
          <p class="text-xs text-slate-400 mb-1">Transaksi</p>
          <p class="text-lg font-semibold text-slate-700">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
        </div>
        <div>
          <p class="text-xs text-slate-400 mb-1">Avg. Nilai</p>
          <p class="text-lg font-semibold text-slate-700">Rp {{ number_format($avgTransaksi, 0, ',', '.') }}</p>
        </div>
      </div>
    </div>
    <div class="mt-4 pt-3 border-t border-slate-50 flex justify-end">
      <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-medium">fact_keuangan_periodik</span>
    </div>
  </div>

  <!-- Grafik Revenue Bulanan -->
  <div class="lg:col-span-2 bg-white border border-slate-200 p-6 rounded-xl shadow-sm flex flex-col justify-between h-full">
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-bold text-slate-800">Revenue Trend</h3>
          <p class="text-sm text-slate-500">Performa pendapatan 12 bulan terakhir</p>
        </div>
        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-medium">fact_keuangan_periodik</span>
      </div>
      <div class="h-[280px] w-full">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Row 2: KPI Penitipan Hari Ini & Grafik Okupansi Harian -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- KPI Penitipan Hari Ini (fact_kapasitas_harian) -->
  <div class="bg-white border border-slate-200 p-6 rounded-xl shadow-sm flex flex-col justify-between h-full relative overflow-hidden">
    <div>
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Okupansi Hari Ini</h3>
        <span class="p-2 bg-orange-50 text-orange-600 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
        </span>
      </div>
      <div class="mb-6">
        <p class="text-3xl font-bold text-slate-800">{{ $penitipanHariIni }} <span class="text-lg font-normal text-slate-400">Ekor</span></p>
        <p class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
      </div>
      
      <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4">
        <div>
          <div class="flex items-center gap-1 mb-1">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            <p class="text-xs text-slate-400">Aktif</p>
          </div>
          <p class="text-lg font-semibold text-slate-700">{{ $penitipanAktif }}</p>
        </div>
        <div>
          <div class="flex items-center gap-1 mb-1">
            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
            <p class="text-xs text-slate-400">Pending</p>
          </div>
          <p class="text-lg font-semibold text-slate-700">{{ $penitipanPending }}</p>
        </div>
      </div>
    </div>
    <div class="mt-4 pt-3 border-t border-slate-50 flex justify-end">
      <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-medium">fact_kapasitas_harian</span>
    </div>
  </div>

  <!-- Grafik Okupansi Harian -->
  <div class="lg:col-span-2 bg-white border border-slate-200 p-6 rounded-xl shadow-sm flex flex-col justify-between h-full">
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-bold text-slate-800">Okupansi Harian</h3>
          <p class="text-sm text-slate-500">Tren 30 hari terakhir</p>
        </div>
        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-medium">fact_kapasitas_harian</span>
      </div>
      <div class="h-[280px] w-full">
        <canvas id="okupansiChart"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Revenue Chart
  const revenueCanvas = document.getElementById('revenueChart');
  if (revenueCanvas) {
    new Chart(revenueCanvas.getContext('2d'), {
      type: 'line',
      data: {
        labels: @json($revenueLabels ?? []),
        datasets: [{
          label: 'Revenue (Rp)',
          data: @json($revenueData ?? []),
          borderColor: 'rgba(59, 130, 246, 1)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          pointRadius: 4,
          pointBackgroundColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 2,
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { 
          y: { 
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
              }
            }
          } 
        },
        plugins: { 
          legend: { display: true, labels: { boxWidth: 12 } },
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  }

  // Okupansi Chart
  const okupansiCanvas = document.getElementById('okupansiChart');
  if (okupansiCanvas) {
    new Chart(okupansiCanvas.getContext('2d'), {
      type: 'line',
      data: {
        labels: @json($okupansiLabels ?? []),
        datasets: [
          {
            label: 'Total Penitipan',
            data: @json($okupansiData ?? []),
            borderColor: 'rgba(99, 102, 241, 1)',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            pointRadius: 3,
            borderWidth: 2,
            tension: 0.3,
            fill: true,
          },
          {
            label: 'Aktif',
            data: @json($okupansiAktif ?? []),
            borderColor: 'rgba(34, 197, 94, 1)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            pointRadius: 3,
            borderWidth: 2,
            tension: 0.3,
            fill: true,
          },
          {
            label: 'Pending',
            data: @json($okupansiPending ?? []),
            borderColor: 'rgba(251, 146, 60, 1)',
            backgroundColor: 'rgba(251, 146, 60, 0.1)',
            pointRadius: 3,
            borderWidth: 2,
            tension: 0.3,
            fill: true,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { 
          y: { 
            beginAtZero: true,
            ticks: {
              stepSize: 5
            }
          } 
        },
        plugins: { 
          legend: { display: true, position: 'top', labels: { boxWidth: 12, usePointStyle: true } },
          tooltip: {
            mode: 'index',
            intersect: false,
          }
        }
      }
    });
  }
});
</script>
@endpush