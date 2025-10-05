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
      <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</div>
      <p class="text-xs text-gray-500">{{ $incomeChange ?? '0%' }} dari bulan lalu</p>
    </div>
    <div class="p-4 border rounded-lg shadow">
      <p class="text-sm font-medium">Total Pengeluaran</p>
      <div class="text-2xl font-bold text-red-600">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</div>
      <p class="text-xs text-gray-500">{{ $expenseChange ?? '0%' }} dari bulan lalu</p>
    </div>
    <div class="p-4 border rounded-lg shadow">
      <p class="text-sm font-medium">Profit Bersih</p>
      <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}</div>
      <p class="text-xs text-gray-500">{{ $profitChange ?? '0%' }} dari bulan lalu</p>
    </div>
    <div class="p-4 border rounded-lg shadow">
      <p class="text-sm font-medium">Margin Profit</p>
      <div class="text-2xl font-bold text-purple-600">{{ $profitMargin ?? '0' }}%</div>
      <p class="text-xs text-gray-500">Target: 50%</p>
    </div>
  </div>

  <!-- Transaksi Terbaru -->
  <div class="p-4 border rounded-lg shadow">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-bold">Transaksi</h2>
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
        @forelse($transactions ?? [] as $transaction)
          <tr class="border-t">
            <td class="p-2">{{ $transaction->id }}</td>
            <td class="p-2">{{ $transaction->description }}</td>
            <td class="p-2">{{ $transaction->date }}</td>
            <td class="p-2"><span class="px-2 py-1 border rounded text-xs">{{ $transaction->method }}</span></td>
            <td class="p-2 {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }} font-medium">
              {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
            </td>
          </tr>
        @empty
          <tr class="border-t">
            <td colspan="5" class="p-8 text-center text-gray-500">
              <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <p class="text-lg font-medium">Belum ada transaksi</p>
              <p class="text-sm">Transaksi akan muncul di sini setelah ada data</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection