@extends('admin.layouts.app')

@section('content')
<div>
  <!-- Header -->
  <header class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-3xl font-bold">PEMBAYARAN</h1>
      <p class="text-gray-600">Lacak semua pembayaran dan transaksi</p>
    </div>
    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
      Ekspor Laporan
    </button>
  </header>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Pendapatan</h3>
        <p class="text-3xl font-bold mt-2">Rp 21M</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Total Pembayaran</h3>
        <p class="text-3xl font-bold mt-2">45</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-600">Pembayaran Pending</h3>
        <p class="text-3xl font-bold mt-2">8</p>
    </div>
  </div>

  <!-- Charts -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Metode Pembayaran</h3>
      <canvas id="paymentMethodChart"></canvas>
    </div>
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
      <h3 class="text-xl font-semibold mb-4">Pendapatan 7 Hari Terakhir</h3>
      <canvas id="dailyRevenueChart"></canvas>
    </div>
  </div>

  <!-- Filters -->
  <div class="mb-6">
    <div class="flex flex-wrap items-center gap-4 p-4 bg-white rounded-lg shadow-md">
      <input type="text" placeholder="Search..." class="flex-grow w-full sm:w-auto px-4 py-2 border rounded-lg">
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Metode</option>
        <option>Transfer</option>
        <option>E_Wallet</option>
        <option>QRIS</option>
      </select>
      <select class="px-4 py-2 border rounded-lg">
        <option>Semua Status</option>
        <option>Lunas</option>
        <option>Pending</option>
        <option>Gagal</option>
      </select>
      <input type="date" class="px-4 py-2 border rounded-lg">
    </div>
  </div>

  <!-- Payments Table -->
   <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
      <h3 class="font-semibold">DAFTAR PEMBAYARAN</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-max">
        <thead class="bg-gray-50 text-left text-sm text-gray-600">
          <tr>
            <th class="p-4">ID Pembayaran</th>
            <th class="p-4">ID Penitipan</th>
            <th class="p-4">Pelanggan</th>
            <th class="p-4">Tanggal Bayar</th>
            <th class="p-4">Jumlah</th>
            <th class="p-4">Metode</th>
            <th class="p-4">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-mono text-sm">PAY-001</td>
            <td class="p-4 font-mono text-sm">PT-001</td>
            <td class="p-4 font-medium">Budi Santoso</td>
            <td class="p-4">28 Sep 2025</td>
            <td class="p-4">Rp 750.000</td>
            <td class="p-4">Transfer</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Lunas</span></td>
          </tr>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-mono text-sm">PAY-002</td>
            <td class="p-4 font-mono text-sm">PT-002</td>
            <td class="p-4 font-medium">Citra Lestari</td>
            <td class="p-4">27 Sep 2025</td>
            <td class="p-4">Rp 400.000</td>
            <td class="p-4">E_Wallet</td>
            <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Payment Method Chart
  const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
  new Chart(paymentMethodCtx, {
    type: 'bar',
    data: {
      labels: ['Transfer Bank', 'Kartu Kredit', 'E-Wallet'],
      datasets: [{
        label: 'Jumlah Transaksi',
        data: [10, 5, 3],
        backgroundColor: [
          'rgba(54, 162, 235, 0.8)',
          'rgba(255, 99, 132, 0.8)',
          'rgba(75, 192, 192, 0.8)'
        ],
        borderColor: '#fff',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      indexAxis: 'y',
      plugins: { legend: { display: false } }
    }
  });

  // Daily Revenue Chart
  const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
  new Chart(dailyRevenueCtx, {
    type: 'line',
    data: {
      labels: ['-6 Hari', '-5 Hari', '-4 Hari', '-3 Hari', '-2 Hari', 'Kemarin', 'Hari Ini'],
      datasets: [{
        label: 'Pendapatan (Rp)',
        data: [2.5, 3, 2, 4, 3.5, 5, 4.5].map(x => x * 1000000), // in millions
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
        y: { beginAtZero: true }
      }
    }
  });
</script>
@endpush
