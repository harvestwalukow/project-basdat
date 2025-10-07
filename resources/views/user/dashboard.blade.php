@extends('layouts.app')

@section('title', 'Dashboard - PawsHotel')

@section('body-class', 'bg-[#FEFBF7] text-[#333333]')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <!-- Success/Error Messages -->
  @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
      <span>✅ {{ session('success') }}</span>
      <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center justify-between">
      <span>❌ {{ session('error') }}</span>
      <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  @endif

  <!-- Welcome Section -->
  <div class="bg-gradient-to-r from-orange-400 to-orange-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
    <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ $user->nama_lengkap ?? session('user_name', 'User') }}! 🐾</h1>
    <p class="text-orange-100">Kelola reservasi dan pantau hewan peliharaan Anda dengan mudah</p>
  </div>

  <!-- Quick Stats -->
  <div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm mb-1">Total Reservasi</p>
          <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">📅</span>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm mb-1">Reservasi Aktif</p>
          <p class="text-3xl font-bold text-gray-800">{{ $stats['aktif'] }}</p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">✅</span>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm mb-1">Hewan Terdaftar</p>
          <p class="text-3xl font-bold text-gray-800">{{ $stats['hewan'] }}</p>
        </div>
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">🐕</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
    <div class="grid md:grid-cols-2 gap-4">
      <a href="{{ route('reservasi') }}" class="flex items-center space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-[#F2784B] hover:bg-orange-50 transition">
        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
          <span class="text-xl">➕</span>
        </div>
        <div>
          <p class="font-semibold text-gray-800">Buat Reservasi Baru</p>
          <p class="text-xs text-gray-500">Pesan kamar untuk hewan peliharaan</p>
        </div>
      </a>

      <a href="{{ url('/') }}" class="flex items-center space-x-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
          <span class="text-xl">🏠</span>
        </div>
        <div>
          <p class="font-semibold text-gray-800">Kembali ke Beranda</p>
          <p class="text-xs text-gray-500">Lihat informasi PawsHotel</p>
        </div>
      </a>
    </div>
  </div>

  <!-- All Reservations -->
  <div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-800">Semua Reservasi</h2>
      <div class="flex gap-2">
        <button onclick="filterReservations('all')" class="filter-btn px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100 active" data-filter="all">
          Semua
        </button>
        <button onclick="filterReservations('pending')" class="filter-btn px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100" data-filter="pending">
          Pending
        </button>
        <button onclick="filterReservations('aktif')" class="filter-btn px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100" data-filter="aktif">
          Aktif
        </button>
        <button onclick="filterReservations('selesai')" class="filter-btn px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100" data-filter="selesai">
          Selesai
        </button>
      </div>
    </div>

    @if($reservations->count() > 0)
      <div class="space-y-4">
        @foreach($reservations as $reservation)
          <div class="reservation-card border border-gray-200 rounded-lg p-4 hover:border-[#F2784B] hover:shadow-md transition" data-status="{{ $reservation->status }}">
            <div class="flex items-start justify-between">
              <div class="flex items-start space-x-3 flex-1">
                @if($reservation->latest_foto)
                  <!-- Show latest photo from update kondisi -->
                  <div class="w-20 h-20 rounded-lg overflow-hidden shadow-md flex-shrink-0 cursor-pointer hover:opacity-80 transition" onclick="openPhotoModal('/{{ $reservation->latest_foto }}', '{{ $reservation->nama_hewan }}', '{{ \Carbon\Carbon::parse($reservation->latest_foto_waktu)->format('d M Y H:i') }}')">
                    <img src="/{{ $reservation->latest_foto }}" alt="{{ $reservation->nama_hewan }}" class="w-full h-full object-cover">
                  </div>
                @else
                  <!-- Show default icon -->
                  <div class="w-12 h-12 bg-gradient-to-br from-{{ $reservation->jenis_hewan == 'cat' ? 'purple' : 'orange' }}-400 to-{{ $reservation->jenis_hewan == 'cat' ? 'purple' : 'orange' }}-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                    {{ $reservation->jenis_hewan == 'cat' ? '🐱' : '🐕' }}
                  </div>
                @endif
                <div class="flex-1">
                  <p class="font-semibold text-gray-800">{{ $reservation->nama_hewan }} - {{ ucfirst($reservation->ras) }}</p>
                  <p class="text-sm text-gray-600">
                    Check-in: {{ \Carbon\Carbon::parse($reservation->tanggal_masuk)->format('d M Y') }} | 
                    Check-out: {{ \Carbon\Carbon::parse($reservation->tanggal_keluar)->format('d M Y') }}
                  </p>
                  <p class="text-xs text-gray-500 mt-1">
                    Paket: {{ $reservation->nama_paket ?? 'Basic' }} | 
                    Total: Rp {{ number_format($reservation->total_biaya, 0, ',', '.') }}
                  </p>
                  @if($reservation->catatan_khusus)
                    <p class="text-xs text-gray-500 mt-1">📝 {{ $reservation->catatan_khusus }}</p>
                  @endif
                  @if($reservation->latest_foto)
                    <p class="text-xs text-blue-600 mt-1">
                      📸 Foto terakhir: {{ \Carbon\Carbon::parse($reservation->latest_foto_waktu)->format('d M Y H:i') }}
                    </p>
                  @endif
                </div>
              </div>
              <div class="text-right">
                @php
                  $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'aktif' => 'bg-green-100 text-green-700',
                    'selesai' => 'bg-gray-100 text-gray-700',
                    'dibatalkan' => 'bg-red-100 text-red-700'
                  ];
                  $statusLabels = [
                    'pending' => 'Pending',
                    'aktif' => 'Aktif',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan'
                  ];
                @endphp
                <span class="px-3 py-1 {{ $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold rounded-full">
                  {{ $statusLabels[$reservation->status] ?? ucfirst($reservation->status) }}
                </span>
                <p class="text-xs text-gray-500 mt-2">ID: #{{ str_pad($reservation->id_penitipan, 6, '0', STR_PAD_LEFT) }}</p>
              </div>
            </div>

            @if($reservation->status_pembayaran)
              <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-600">Status Pembayaran:</span>
                  @php
                    $paymentColors = [
                      'pending' => 'text-yellow-700',
                      'lunas' => 'text-green-700',
                      'gagal' => 'text-red-700'
                    ];
                  @endphp
                  <span class="font-semibold {{ $paymentColors[$reservation->status_pembayaran] ?? 'text-gray-700' }}">
                    {{ ucfirst($reservation->status_pembayaran) }}
                  </span>
                </div>
                @if($reservation->nomor_transaksi)
                  <p class="text-xs text-gray-500 mt-1">Transaksi: {{ $reservation->nomor_transaksi }}</p>
                @endif
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center py-12">
        <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
          <span class="text-4xl">📋</span>
        </div>
        <p class="text-gray-600 mb-2">Belum ada reservasi</p>
        <p class="text-sm text-gray-500 mb-4">Buat reservasi pertama Anda sekarang!</p>
        <a href="{{ route('reservasi') }}" class="inline-block px-6 py-2 bg-[#F2784B] hover:bg-[#e0673d] text-white rounded-lg font-semibold">
          Buat Reservasi
        </a>
      </div>
    @endif
  </div>

  <!-- Tips & Info -->
  <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
    <h3 class="font-semibold text-blue-800 mb-2">💡 Tips untuk Reservasi Anda</h3>
    <ul class="text-sm text-blue-700 space-y-1">
      <li>• Pastikan vaksin hewan peliharaan Anda sudah lengkap sebelum check-in</li>
      <li>• Reservasi minimal 3 hari sebelum tanggal check-in untuk ketersediaan terbaik</li>
      <li>• Hubungi customer service untuk permintaan khusus atau pertanyaan</li>
    </ul>
  </div>
</div>

<!-- Photo Modal/Lightbox -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 hidden items-center justify-center z-50 p-4" onclick="closePhotoModal()">
  <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
    <!-- Close Button -->
    <button onclick="closePhotoModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition">
      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    
    <!-- Photo Container -->
    <div class="bg-white rounded-lg overflow-hidden shadow-2xl">
      <img id="modalPhoto" src="" alt="" class="w-full h-auto max-h-[80vh] object-contain">
      <div class="p-4 bg-gray-50">
        <p id="modalPetName" class="font-semibold text-gray-800 text-lg"></p>
        <p id="modalPhotoDate" class="text-sm text-gray-600 mt-1"></p>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function filterReservations(status) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.classList.remove('active', 'bg-[#F2784B]', 'text-white', 'border-[#F2784B]');
      btn.classList.add('border-gray-300', 'hover:bg-gray-100');
    });
    
    const activeBtn = document.querySelector(`[data-filter="${status}"]`);
    activeBtn.classList.add('active', 'bg-[#F2784B]', 'text-white', 'border-[#F2784B]');
    activeBtn.classList.remove('border-gray-300', 'hover:bg-gray-100');

    // Filter cards
    document.querySelectorAll('.reservation-card').forEach(card => {
      if (status === 'all' || card.dataset.status === status) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  // Photo Modal Functions
  function openPhotoModal(photoUrl, petName, photoDate) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('modalPhoto').alt = petName;
    document.getElementById('modalPetName').textContent = petName;
    document.getElementById('modalPhotoDate').textContent = '📸 Diambil pada: ' + photoDate;
    
    const modal = document.getElementById('photoModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; // Prevent background scroll
  }

  function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto'; // Restore scroll
  }

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closePhotoModal();
    }
  });
</script>
@endpush
@endsection
