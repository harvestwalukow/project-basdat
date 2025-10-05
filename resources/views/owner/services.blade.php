@extends('owner.layouts.app')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Manajemen Layanan</h1>
      <p class="text-gray-500">Kelola layanan dan paket yang ditawarkan</p>
    </div>
    <button class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
      </svg>
      Tambah Layanan
    </button>
  </div>

  {{-- Category Stats --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Penitipan</p>
          <p class="text-lg font-semibold">2 layanan</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-green-600 font-medium">Rp 45M</p>
          <p class="text-xs text-gray-400">2100 booking</p>
        </div>
      </div>
    </div>
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Grooming</p>
          <p class="text-lg font-semibold">2 layanan</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-green-600 font-medium">Rp 15M</p>
          <p class="text-xs text-gray-400">970 booking</p>
        </div>
      </div>
    </div>
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Kolam Renang</p>
          <p class="text-lg font-semibold">1 layanan</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-green-600 font-medium">Rp 3M</p>
          <p class="text-xs text-gray-400">180 booking</p>
        </div>
      </div>
    </div>
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Antar Jemput</p>
          <p class="text-lg font-semibold">1 layanan</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-green-600 font-medium">Rp 5M</p>
          <p class="text-xs text-gray-400">320 booking</p>
        </div>
      </div>
    </div>
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Enrichment Extra</p>
          <p class="text-lg font-semibold">1 layanan</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-green-600 font-medium">Rp 4M</p>
          <p class="text-xs text-gray-400">270 booking</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabs --}}
  <div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8">
      <a href="#" id="tab-daftar" class="text-primary border-primary border-b-2 px-1 py-4 text-sm font-medium">Daftar Layanan</a>
      <a href="#" id="tab-analitik" class="text-gray-500 hover:text-gray-700 border-transparent border-b-2 px-1 py-4 text-sm font-medium">Analitik</a>
    </nav>
  </div>

  {{-- Content Daftar Layanan --}}
  <div id="content-daftar" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
    {{-- Penitipan Standard --}}
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Penitipan Standard</h3>
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Penitipan</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Penitipan harian dengan fasilitas kandang standard, makanan 2x sehari, dan perawatan dasar</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 150.000</p>
          <p class="text-xs text-gray-400">Per hari</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400">⭐</span> <span class="text-sm font-medium">4.7</span>
          </div>
          <p class="text-xs text-gray-400">1250 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm hover:bg-gray-50">🗑</button>
      </div>
    </div>

    {{-- Penitipan Premium --}}
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Penitipan Premium</h3>
            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Penitipan</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Kandang premium AC, makanan premium 3x sehari, grooming mingguan, dan play time</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 250.000</p>
          <p class="text-xs text-gray-400">Per hari</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400">⭐</span> <span class="text-sm font-medium">4.9</span>
          </div>
          <p class="text-xs text-gray-400">850 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm hover:bg-gray-50">🗑</button>
      </div>
    </div>

    {{-- Grooming Basic --}}
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-pink-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Grooming Basic</h3>
            <span class="bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded">Grooming</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Mandi, sisir, potong kuku, dan pembersihan telinga</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 75.000</p>
          <p class="text-xs text-gray-400">Per sesi</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400">⭐</span> <span class="text-sm font-medium">4.6</span>
          </div>
          <p class="text-xs text-gray-400">520 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm hover:bg-gray-50">🗑</button>
      </div>
    </div>

    {{-- Grooming Premium --}}
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-rose-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Grooming Premium</h3>
            <span class="bg-rose-100 text-rose-800 text-xs px-2 py-1 rounded">Grooming</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Paket lengkap + styling, spa treatment, dan aromaterapi</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 150.000</p>
          <p class="text-xs text-gray-400">Per sesi</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400">⭐</span> <span class="text-sm font-medium">4.8</span>
          </div>
          <p class="text-xs text-gray-400">450 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm hover:bg-gray-50">🗑</button>
      </div>
    </div>

    {{-- Kolam Renang --}}
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-cyan-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Kolam Renang</h3>
            <span class="bg-cyan-100 text-cyan-800 text-xs px-2 py-1 rounded">Tambahan</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Sesi berenang dengan pengawasan, termasuk handuk dan hair dryer</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 50.000</p>
          <p class="text-xs text-gray-400">Per sesi</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400">⭐</span> <span class="text-sm font-medium">4.7</span>
          </div>
          <p class="text-xs text-gray-400">180 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm hover:bg-gray-50">🗑</button>
      </div>
    </div>

    {{-- Antar Jemput --}}
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Antar Jemput</h3>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Tambahan</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Layanan antar jemput dalam radius 10km, mobil ber-AC</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 75.000</p>
          <p class="text-xs text-gray-400">Per trip</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400">⭐</span> <span class="text-sm font-medium">4.8</span>
          </div>
          <p class="text-xs text-gray-400">320 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm hover:bg-gray-50">🗑</button>
      </div>
    </div>

    {{-- Enrichment Extra --}}
    <div class="bg-white shadow rounded-2xl p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-orange-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Enrichment Extra</h3>
            <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded">Tambahan</span>
          </div>
        </div>
        <input type="checkbox" checked class="toggle-switch">
      </div>
      <p class="text-sm text-gray-500 mt-3">Latihan fisik dan stimulasi mental dengan trainer profesional</p>

      <div class="flex items-center justify-between mt-4">
        <div>
          <p class="text-lg font-bold text-green-600">Rp 100.000</p>
          <p class="text-xs text-gray-400">Per sesi</p>
        </div>
        <div class="text-right">
          <div class="flex items-center gap-1">
            <span class="text-yellow-400">⭐</span> <span class="text-sm font-medium">4.9</span>
          </div>
          <p class="text-xs text-gray-400">270 booking</p>
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Edit</button>
        <button class="flex-1 border rounded-lg py-1 text-sm hover:bg-gray-50">Booking</button>
        <button class="border rounded-lg py-1 px-3 text-sm hover:bg-gray-50">🗑</button>
      </div>
    </div>
  </div>

  {{-- Content Analitik --}}
  <div id="content-analitik" class="mt-6 hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- Pie Chart: Penitipan Premium vs Basic --}}
      <div class="bg-white shadow rounded-2xl p-6">
        <h3 class="font-semibold text-lg mb-4">Perbandingan Booking Penitipan</h3>
        <div class="flex items-center justify-center" style="height: 300px;">
          <canvas id="pieChartPenitipan"></canvas>
        </div>
        <div class="mt-4 space-y-2">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-4 h-4 bg-purple-500 rounded"></div>
              <span class="text-sm">Penitipan Premium</span>
            </div>
            <span class="text-sm font-medium">40.5% (850 booking)</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-4 h-4 bg-blue-500 rounded"></div>
              <span class="text-sm">Penitipan Basic</span>
            </div>
            <span class="text-sm font-medium">59.5% (1250 booking)</span>
          </div>
        </div>
      </div>

      {{-- Bar Chart: Semua Layanan --}}
      <div class="bg-white shadow rounded-2xl p-6">
        <h3 class="font-semibold text-lg mb-4">Total Booking per Layanan</h3>
        <div class="flex items-end justify-between h-64 gap-2">
          <div class="flex flex-col items-center flex-1 h-full justify-end">
            <div class="bg-blue-500 w-full rounded-t-lg" style="height: 75%;"></div>
            <span class="text-xs mt-2 text-center">Basic</span>
            <span class="text-xs font-medium">1250</span>
          </div>
          <div class="flex flex-col items-center flex-1 h-full justify-end">
            <div class="bg-purple-500 w-full rounded-t-lg" style="height: 51%;"></div>
            <span class="text-xs mt-2 text-center">Premium</span>
            <span class="text-xs font-medium">850</span>
          </div>
          <div class="flex flex-col items-center flex-1 h-full justify-end">
            <div class="bg-pink-500 w-full rounded-t-lg" style="height: 31%;"></div>
            <span class="text-xs mt-2 text-center">Grooming B</span>
            <span class="text-xs font-medium">520</span>
          </div>
          <div class="flex flex-col items-center flex-1 h-full justify-end">
            <div class="bg-rose-500 w-full rounded-t-lg" style="height: 27%;"></div>
            <span class="text-xs mt-2 text-center">Grooming P</span>
            <span class="text-xs font-medium">450</span>
          </div>
          <div class="flex flex-col items-center flex-1 h-full justify-end">
            <div class="bg-green-500 w-full rounded-t-lg" style="height: 19%;"></div>
            <span class="text-xs mt-2 text-center">Antar</span>
            <span class="text-xs font-medium">320</span>
          </div>
          <div class="flex flex-col items-center flex-1 h-full justify-end">
            <div class="bg-orange-500 w-full rounded-t-lg" style="height: 16%;"></div>
            <span class="text-xs mt-2 text-center">Enrichment</span>
            <span class="text-xs font-medium">270</span>
          </div>
          <div class="flex flex-col items-center flex-1 h-full justify-end">
            <div class="bg-cyan-500 w-full rounded-t-lg" style="height: 11%;"></div>
            <span class="text-xs mt-2 text-center">Kolam</span>
            <span class="text-xs font-medium">180</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Tab switching
document.getElementById('tab-daftar').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('content-daftar').classList.remove('hidden');
  document.getElementById('content-analitik').classList.add('hidden');
  this.classList.add('text-primary', 'border-primary');
  this.classList.remove('text-gray-500', 'border-transparent');
  document.getElementById('tab-analitik').classList.remove('text-primary', 'border-primary');
  document.getElementById('tab-analitik').classList.add('text-gray-500', 'border-transparent');
});

document.getElementById('tab-analitik').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('content-analitik').classList.remove('hidden');
  document.getElementById('content-daftar').classList.add('hidden');
  this.classList.add('text-primary', 'border-primary');
  this.classList.remove('text-gray-500', 'border-transparent');
  document.getElementById('tab-daftar').classList.remove('text-primary', 'border-primary');
  document.getElementById('tab-daftar').classList.add('text-gray-500', 'border-transparent');
  
  if (!window.chartInitialized) {
    initCharts();
    window.chartInitialized = true;
  }
});

function initCharts() {
  const ctx = document.getElementById('pieChartPenitipan');
  if (ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Penitipan Premium', 'Penitipan Basic'],
        datasets: [{
          data: [850, 1250],
          backgroundColor: ['#A855F7', '#3B82F6'],
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  }
}
</script>
@endpush