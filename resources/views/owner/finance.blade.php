@extends('owner.layouts.App')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Manajemen Keuangan</h1>
      <p class="text-gray-500">Monitor pendapatan, pengeluaran, dan laporan keuangan</p>
    </div>
    <div class="flex gap-2">
      <button class="flex items-center px-4 py-2 border rounded-md text-sm hover:bg-gray-100">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 4v16m8-8H4" />
        </svg>
        Export PDF
      </button>
      <button class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 8v8m-4-4h8" />
        </svg>
        Buat Laporan
      </button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="p-4 border rounded-lg shadow">
      <p class="text-sm font-medium">Total Pendapatan</p>
      <div class="text-2xl font-bold text-green-600">Rp 91.000.000</div>
      <p class="text-xs text-gray-500">+15.2% dari bulan lalu</p>
    </div>
    <div class="p-4 border rounded-lg shadow">
      <p class="text-sm font-medium">Total Pengeluaran</p>
      <div class="text-2xl font-bold text-red-600">Rp 41.000.000</div>
      <p class="text-xs text-gray-500">+8.3% dari bulan lalu</p>
    </div>
    <div class="p-4 border rounded-lg shadow">
      <p class="text-sm font-medium">Profit Bersih</p>
      <div class="text-2xl font-bold text-blue-600">Rp 50.000.000</div>
      <p class="text-xs text-gray-500">+23.1% dari bulan lalu</p>
    </div>
    <div class="p-4 border rounded-lg shadow">
      <p class="text-sm font-medium">Margin Profit</p>
      <div class="text-2xl font-bold text-purple-600">54.9%</div>
      <p class="text-xs text-gray-500">Target: 50%</p>
    </div>
  </div>

  <!-- Transaksi Terbaru -->
  <div class="p-4 border rounded-lg shadow">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-bold">Transaksi Terbaru</h2>
      <div class="flex gap-2">
        <select class="border rounded-md px-2 py-1 text-sm">
          <option>Semua</option>
          <option>Pemasukan</option>
          <option>Pengeluaran</option>
        </select>
        <button class="flex items-center px-2 py-1 border rounded-md text-sm hover:bg-gray-100">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M8 7V3m8 4V3m-9 9h10m-12 8h14" />
          </svg>
          Filter
        </button>
      </div>
    </div>
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 text-sm">ID</th>
          <th class="p-2 text-sm">Deskripsi</th>
          <th class="p-2 text-sm">Tanggal</th>
          <th class="p-2 text-sm">Metode</th>
          <th class="p-2 text-sm">Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-t">
          <td class="p-2">TRX001</td>
          <td class="p-2">Pembayaran Penitipan - Sarah Johnson</td>
          <td class="p-2">2024-01-15</td>
          <td class="p-2"><span class="px-2 py-1 border rounded text-xs">Transfer Bank</span></td>
          <td class="p-2 text-green-600 font-medium">+Rp 2.500.000</td>
        </tr>
        <tr class="border-t">
          <td class="p-2">TRX002</td>
          <td class="p-2">Pembelian Makanan Hewan</td>
          <td class="p-2">2024-01-15</td>
          <td class="p-2"><span class="px-2 py-1 border rounded text-xs">Cash</span></td>
          <td class="p-2 text-red-600 font-medium">-Rp 1.200.000</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
