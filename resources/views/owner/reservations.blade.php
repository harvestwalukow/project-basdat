@extends('owner.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Manajemen Reservasi</h1>
      <p class="text-gray-500">Kelola reservasi dan penitipan hewan</p>
    </div>
    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 4h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
      Reservasi Baru
    </button>
  </div>

  <!-- Tabs -->
  <div class="bg-white rounded-lg shadow">
    <div class="border-b border-gray-200">
      <nav class="flex space-x-8 px-6">
        <a href="{{ route('owner.reservations', 'semua') }}" 
           class="border-b-2 {{ $tab === 'semua' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
          Semua Reservasi
        </a>
        <a href="{{ route('owner.reservations', 'today') }}" 
           class="border-b-2 {{ $tab === 'today' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
          Hari Ini
        </a>
        <a href="{{ route('owner.reservations', 'upcoming') }}" 
           class="border-b-2 {{ $tab === 'upcoming' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
          Akan Datang
        </a>
        <a href="{{ route('owner.reservations', 'selesai') }}" 
           class="border-b-2 {{ $tab === 'selesai' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
          Selesai
        </a>
      </nav>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <div class="relative">
            <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
              type="text"
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
              placeholder="Cari berdasarkan nama pemilik, hewan, atau ID reservasi..."
              id="searchInput"
            />
          </div>
        </div>
        <div class="w-48">
          <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" id="statusFilter">
            <option value="all">Semua Status</option>
            <option value="pending">Menunggu</option>
            <option value="confirmed">Dikonfirmasi</option>
            <option value="checkedin">Check-in</option>
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Reservations List -->
    <div class="p-6 space-y-4" id="reservationsList">
      @forelse($reservations ?? [] as $reservation)
        <div class="border border-gray-200 rounded-lg p-4 reservation-card" data-status="{{ $reservation->status }}">
          <div class="flex flex-col md:flex-row justify-between gap-4">
            <div class="flex-1 space-y-3">
              <div class="flex items-center gap-3">
                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded reservation-id">{{ $reservation->id }}</span>
                <span class="px-2 py-1 
                  @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                  @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                  @elseif($reservation->status === 'checkedin') bg-blue-100 text-blue-800
                  @elseif($reservation->status === 'completed') bg-gray-100 text-gray-800
                  @else bg-red-100 text-red-800
                  @endif
                  text-xs rounded flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ ucfirst($reservation->status_label) }}
                </span>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <h3 class="font-semibold">{{ $reservation->owner_name }}</h3>
                  <p class="text-sm text-gray-500 phone">{{ $reservation->phone }}</p>
                  <p class="text-sm"><span class="font-medium">Hewan:</span> {{ $reservation->pet_name }} ({{ $reservation->pet_breed }})</p>
                </div>
                
                <div>
                  <p class="text-sm"><span class="font-medium">Layanan:</span> {{ $reservation->service }}</p>
                  <p class="text-sm"><span class="font-medium">Check-in:</span> {{ $reservation->checkin_date }}</p>
                  <p class="text-sm"><span class="font-medium">Check-out:</span> {{ $reservation->checkout_date }}</p>
                  <p class="text-sm"><span class="font-medium">Durasi:</span> {{ $reservation->duration }} hari</p>
                </div>
              </div>
              
              @if($reservation->notes)
              <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-sm"><span class="font-medium">Catatan:</span> {{ $reservation->notes }}</p>
              </div>
              @endif
            </div>
            
            <div class="flex flex-col justify-between items-end gap-2">
              <div class="text-lg font-bold text-green-600">Rp {{ number_format($reservation->total, 0, ',', '.') }}</div>
              <div class="flex gap-2">
                <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-detail" data-id="{{ $reservation->id }}">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  Detail
                </button>
                <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-edit" data-id="{{ $reservation->id }}">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  Edit
                </button>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-12 text-gray-500">
          <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 4h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <p class="text-lg font-medium">Belum ada reservasi</p>
          <p class="text-sm">Reservasi akan muncul di sini setelah ada data</p>
        </div>
      @endforelse
    </div>
  </div>
</div>
   
@endsection

@push('scripts')
<script>
// Search and filter functionality tetap sama seperti sebelumnya
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  
  function applyFilters() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    const selectedStatus = statusFilter.value;
    const cards = document.querySelectorAll('.reservation-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
      const ownerName = card.querySelector('h3')?.textContent.toLowerCase() || '';
      const reservationId = card.querySelector('.reservation-id')?.textContent.toLowerCase() || '';
      const phone = card.querySelector('.phone')?.textContent.toLowerCase() || '';
      const status = card.dataset.status;
      
      const searchIn = `${ownerName} ${reservationId} ${phone}`;
      const matchesSearch = searchTerm === '' || searchIn.includes(searchTerm);
      const matchesStatus = selectedStatus === 'all' || status === selectedStatus;
      
      if (matchesSearch && matchesStatus) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });
    
    updateNoResultsMessage(visibleCount);
  }
  
  searchInput.addEventListener('input', applyFilters);
  statusFilter.addEventListener('change', applyFilters);
  
  function updateNoResultsMessage(visibleCount) {
    let noResultsDiv = document.getElementById('noResults');
    
    if (visibleCount === 0 && document.querySelectorAll('.reservation-card').length > 0) {
      if (!noResultsDiv) {
        noResultsDiv = document.createElement('div');
        noResultsDiv.id = 'noResults';
        noResultsDiv.className = 'text-center py-8 text-gray-500';
        noResultsDiv.innerHTML = `
          <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <p class="text-lg font-medium">Tidak ada reservasi ditemukan</p>
          <p class="text-sm">Coba ubah kriteria pencarian atau filter</p>
        `;
        document.getElementById('reservationsList').appendChild(noResultsDiv);
      }
      noResultsDiv.style.display = 'block';
    } else {
      if (noResultsDiv) {
        noResultsDiv.style.display = 'none';
      }
    }
  }
});
</script>
@endpush