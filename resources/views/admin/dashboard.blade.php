@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-bold border-b pb-4 mb-6">DASHBOARD</h1>

<!-- Row 1: KPI Revenue & Grafik Revenue Bulanan -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
  <!-- KPI Revenue (fact_keuangan_periodik) -->
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-slate-800">KPI Revenue</h3>
    <p class="text-xs text-slate-500 mb-2">Bulan Ini (fact_keuangan_periodik)</p>
    
    <div class="space-y-3">
      <div>
        <p class="text-xs text-slate-500">Total Revenue</p>
        <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
      </div>
      
      <div>
        <p class="text-xs text-slate-500">Total Transaksi</p>
        <p class="text-xl font-semibold text-slate-700">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
      </div>
      
      <div>
        <p class="text-xs text-slate-500">Rata-rata per Transaksi</p>
        <p class="text-lg font-medium text-slate-600">Rp {{ number_format($avgTransaksi, 0, ',', '.') }}</p>
      </div>
    </div>
  </div>

  <!-- Grafik Revenue Bulanan -->
  <div class="lg:col-span-2 bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-lg font-semibold mb-2 text-slate-800">Grafik Revenue Bulanan</h3>
    <p class="text-xs text-slate-500 mb-4">12 Bulan Terakhir (fact_keuangan_periodik)</p>
    <div class="h-[280px]">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>
</div>

<!-- Row 2: KPI Penitipan Hari Ini & Grafik Okupansi Harian -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- KPI Penitipan Hari Ini (fact_kapasitas_harian) -->
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-slate-800">KPI Penitipan Hari Ini</h3>
    <p class="text-xs text-slate-500 mb-2">{{ \Carbon\Carbon::now()->format('d M Y') }} (fact_kapasitas_harian)</p>
    
    <div class="space-y-3">
      <div>
        <p class="text-xs text-slate-500">Total Penitipan</p>
        <p class="text-2xl font-bold text-slate-800">{{ $penitipanHariIni }}</p>
      </div>
      
      <div>
        <p class="text-xs text-slate-500">Penitipan Aktif</p>
        <p class="text-xl font-semibold text-green-600">{{ $penitipanAktif }}</p>
      </div>
      
      <div>
        <p class="text-xs text-slate-500">Penitipan Pending</p>
        <p class="text-lg font-medium text-orange-600">{{ $penitipanPending }}</p>
      </div>
    </div>
  </div>

  <!-- Grafik Okupansi Harian -->
  <div class="lg:col-span-2 bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-lg font-semibold mb-2 text-slate-800">Grafik Okupansi Harian</h3>
    <p class="text-xs text-slate-500 mb-4">30 Hari Terakhir (fact_kapasitas_harian)</p>
    <div class="h-[280px]">
      <canvas id="okupansiChart"></canvas>
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