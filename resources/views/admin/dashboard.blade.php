@extends('admin.layouts.app')

@section('content')
<h1 class="text-3xl font-bold border-b pb-4 mb-6">DASHBOARD</h1>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Penitipan Aktif</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">{{ $totalPenitipanAktif }}</p>
  </div>
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Total Hewan</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">{{ $totalHewan }}</p>
  </div>
  <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-sm font-medium text-slate-500">Total Pengguna</h3>
    <p class="text-3xl font-extrabold mt-2 text-slate-800">{{ $totalPengguna }}</p>
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
    <div class="space-y-4 overflow-y-auto max-h-[300px]">
      @forelse($todaySchedule as $schedule)
        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
          <p class="font-semibold text-sm text-slate-800">{{ $schedule->hewan->nama_hewan }}</p>
          <p class="text-xs text-slate-600">Pemilik: {{ $schedule->pemilik->nama_lengkap }}</p>
          <p class="text-xs text-slate-500">
            @if(\Carbon\Carbon::parse($schedule->tanggal_masuk)->isToday())
              <span class="text-green-600">✓ Check-in</span>
            @endif
            @if(\Carbon\Carbon::parse($schedule->tanggal_keluar)->isToday())
              <span class="text-blue-600">← Check-out</span>
            @endif
          </p>
        </div>
      @empty
        <p class="text-sm text-slate-500">Tidak ada jadwal hari ini</p>
      @endforelse
    </div>
  </div>
</div>

<!-- Bawah: Update Kondisi (full width) -->
<div class="grid grid-cols-1 lg:grid-cols-3">
  <div class="lg:col-span-3 bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
    <h3 class="text-xl font-semibold mb-4 text-slate-800">Update Kondisi Terbaru</h3>

    <!-- Tinggi diperpanjang + scroll kalau isi memanjang -->
    <ul class="space-y-3 max-h-[520px] overflow-y-auto pr-2">
      @forelse($latestUpdates as $update)
        <li class="flex items-start gap-3 p-4 bg-slate-50 rounded-lg border border-slate-200">
          <div class="flex-shrink-0 w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold">
            {{ substr($update->penitipan->hewan->nama_hewan, 0, 1) }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-800">{{ $update->penitipan->hewan->nama_hewan }}</p>
            <p class="text-xs text-slate-600">Kondisi: {{ $update->kondisi_hewan }}</p>
            <p class="text-xs text-slate-500">Aktivitas: {{ Str::limit($update->aktivitas_hari_ini, 50) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $update->waktu_update->diffForHumans() }} - {{ $update->staff->nama_lengkap }}</p>
          </div>
        </li>
      @empty
        <li class="text-sm text-slate-500 text-center py-4">Belum ada update kondisi</li>
      @endforelse
    </ul>
  </div>
</div>
@endsection

@push('scripts')
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'line',
  data: {
    labels: @json($revenueLabels),
    datasets: [{
      label: 'Pendapatan (Rp)',
      data: @json($revenueData),
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