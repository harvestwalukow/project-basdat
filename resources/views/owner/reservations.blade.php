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
        <a href="{{ route('owner.reservations', 'semua') }}" class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
          Semua Reservasi
        </a>
        <a href="{{ route('owner.reservations', 'today') }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
          Hari Ini
        </a>
        <a href="{{ route('owner.reservations', 'upcoming') }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
          Akan Datang
        </a>
        <a href="{{ route('owner.reservations', 'selesai') }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
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
      @if ($tab === 'semua')
      <!-- Sample Reservation Cards -->
      <div class="border border-gray-200 rounded-lg p-4 reservation-card" data-status="confirmed">
        <div class="flex flex-col md:flex-row justify-between gap-4">
          <div class="flex-1 space-y-3">
            <div class="flex items-center gap-3">
              <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded reservation-id">RES001</span>
              <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Dikonfirmasi
              </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h3 class="font-semibold">Sarah Johnson</h3>
                <p class="text-sm text-gray-500 phone">081234567890</p>
                <p class="text-sm"><span class="font-medium">Hewan:</span> Buddy (Golden Retriever)</p>
              </div>
              
              <div>
                <p class="text-sm"><span class="font-medium">Layanan:</span> Penitipan Premium</p>
                <p class="text-sm"><span class="font-medium">Check-in:</span> 2024-01-15</p>
                <p class="text-sm"><span class="font-medium">Check-out:</span> 2024-01-20</p>
                <p class="text-sm"><span class="font-medium">Durasi:</span> 5 hari</p>
              </div>
            </div>
            
            <div class="bg-gray-50 p-3 rounded-lg">
              <p class="text-sm"><span class="font-medium">Catatan:</span> Alergi makanan tertentu</p>
            </div>
          </div>
          
          <div class="flex flex-col justify-between items-end gap-2">
            <div class="text-lg font-bold text-green-600">Rp 2.500.000</div>
            <div class="flex gap-2">
              <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-detail" data-id="RES001">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detail
              </button>
              <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-edit" data-id="RES001">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="border border-gray-200 rounded-lg p-4 reservation-card" data-status="pending">
        <div class="flex flex-col md:flex-row justify-between gap-4">
          <div class="flex-1 space-y-3">
            <div class="flex items-center gap-3">
              <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded reservation-id">RES002</span>
              <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Menunggu
              </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h3 class="font-semibold">Michael Chen</h3>
                <p class="text-sm text-gray-500 phone">081298765432</p>
                <p class="text-sm"><span class="font-medium">Hewan:</span> Whiskers (Persian Cat)</p>
              </div>
              
              <div>
                <p class="text-sm"><span class="font-medium">Layanan:</span> Penitipan Standard + Grooming</p>
                <p class="text-sm"><span class="font-medium">Check-in:</span> 2024-01-16</p>
                <p class="text-sm"><span class="font-medium">Check-out:</span> 2024-01-18</p>
                <p class="text-sm"><span class="font-medium">Durasi:</span> 2 hari</p>
              </div>
            </div>
            
            <div class="bg-gray-50 p-3 rounded-lg">
              <p class="text-sm"><span class="font-medium">Catatan:</span> Pertama kali titip</p>
            </div>
          </div>
          
          <div class="flex flex-col justify-between items-end gap-2">
            <div class="text-lg font-bold text-green-600">Rp 1.200.000</div>
            <div class="flex gap-2">
              <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-detail" data-id="RES002">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detail
              </button>
              <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-edit" data-id="RES002">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="border border-gray-200 rounded-lg p-4 reservation-card" data-status="checkedin">
        <div class="flex flex-col md:flex-row justify-between gap-4">
          <div class="flex-1 space-y-3">
            <div class="flex items-center gap-3">
              <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded reservation-id">RES003</span>
              <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Check-in
              </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h3 class="font-semibold">Lisa Brown</h3>
                <p class="text-sm text-gray-500 phone">081356789012</p>
                <p class="text-sm"><span class="font-medium">Hewan:</span> Max (Labrador)</p>
              </div>
              
              <div>
                <p class="text-sm"><span class="font-medium">Layanan:</span> Penitipan + Training</p>
                <p class="text-sm"><span class="font-medium">Check-in:</span> 2024-01-17</p>
                <p class="text-sm"><span class="font-medium">Check-out:</span> 2024-01-25</p>
                <p class="text-sm"><span class="font-medium">Durasi:</span> 8 hari</p>
              </div>
            </div>
            
            <div class="bg-gray-50 p-3 rounded-lg">
              <p class="text-sm"><span class="font-medium">Catatan:</span> Latihan basic commands</p>
            </div>
          </div>
          
          <div class="flex flex-col justify-between items-end gap-2">
            <div class="text-lg font-bold text-green-600">Rp 4.800.000</div>
            <div class="flex gap-2">
              <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-detail" data-id="RES003">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detail
              </button>
              <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 flex items-center btn-edit" data-id="RES003">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>
        
      @elseif ($tab === 'today')
        <h2 class="text-xl font-bold">Reservasi Hari Ini</h2>
        <p class="text-gray-500">Daftar reservasi untuk hari ini.</p>
      
      @elseif ($tab === 'upcoming')
        <h2 class="text-xl font-bold">Reservasi Akan Datang</h2>
        <p class="text-gray-500">Daftar reservasi 7 hari ke depan.</p>
        
      @elseif ($tab === 'selesai')
        <h2 class="text-xl font-bold">Reservasi Selesai</h2>
        <p class="text-gray-500">Daftar reservasi yang sudah selesai.</p>
      @endif
    </div>
  </div>
</div>
   
@endsection

@push('scripts')
<script>
// Search functionality - Enhanced
document.getElementById('searchInput').addEventListener('input', function() {
  const searchTerm = this.value.toLowerCase().trim();
  const cards = document.querySelectorAll('.reservation-card');
  let visibleCount = 0;
  
  cards.forEach(card => {
    const ownerName = card.querySelector('h3').textContent.toLowerCase();
    const petName = card.querySelector('span[class*="font-medium"]').textContent.toLowerCase();
    const reservationId = card.querySelector('.reservation-id').textContent.toLowerCase();
    const phone = card.querySelector('.phone').textContent.toLowerCase();
    
    const searchIn = `${ownerName} ${petName} ${reservationId} ${phone}`;
    
    if (searchTerm === '' || searchIn.includes(searchTerm)) {
      card.style.display = 'block';
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });
  
  // Show/hide no results message
  updateNoResultsMessage(visibleCount);
});

// Status filter functionality - Enhanced
document.getElementById('statusFilter').addEventListener('change', function() {
  const selectedStatus = this.value;
  const cards = document.querySelectorAll('.reservation-card');
  let visibleCount = 0;
  
  cards.forEach(card => {
    const status = card.dataset.status;
    if (selectedStatus === 'all' || status === selectedStatus) {
      card.style.display = 'block';
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });
  
  updateNoResultsMessage(visibleCount);
});

// Combined search and filter
function applyFilters() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
  const selectedStatus = document.getElementById('statusFilter').value;
  const cards = document.querySelectorAll('.reservation-card');
  let visibleCount = 0;
  
  cards.forEach(card => {
    const ownerName = card.querySelector('h3').textContent.toLowerCase();
    const petName = card.querySelector('span[class*="font-medium"]').textContent.toLowerCase();
    const reservationId = card.querySelector('.reservation-id').textContent.toLowerCase();
    const phone = card.querySelector('.phone').textContent.toLowerCase();
    const status = card.dataset.status;
    
    const searchIn = `${ownerName} ${petName} ${reservationId} ${phone}`;
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

// Update both event listeners to use combined filter
document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('statusFilter').addEventListener('change', applyFilters);

// No results message
function updateNoResultsMessage(visibleCount) {
  let noResultsDiv = document.getElementById('noResults');
  
  if (visibleCount === 0) {
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

// Detail functionality
function showDetail(reservationId) {
  // Simulasi data detail (nanti bisa diganti dengan AJAX call)
  const reservationData = {
    'RES001': {
      id: 'RES001',
      owner: 'Sarah Johnson',
      phone: '081234567890',
      email: 'sarah.johnson@email.com',
      pet: 'Buddy',
      breed: 'Golden Retriever',
      age: '3 tahun',
      weight: '25 kg',
      service: 'Penitipan Premium',
      checkin: '2024-01-15',
      checkout: '2024-01-20',
      total: 'Rp 2.500.000',
      status: 'Dikonfirmasi',
      notes: 'Alergi makanan tertentu'
    }
  };
  
  const data = reservationData[reservationId] || reservationData['RES001'];
  
  // Buat modal detail
  const modalHTML = `
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">Detail Reservasi ${data.id}</h3>
          <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <h4 class="font-semibold text-gray-700">Informasi Pemilik</h4>
              <p><strong>Nama:</strong> ${data.owner}</p>
              <p><strong>Telepon:</strong> ${data.phone}</p>
              <p><strong>Email:</strong> ${data.email}</p>
            </div>
            <div>
              <h4 class="font-semibold text-gray-700">Informasi Hewan</h4>
              <p><strong>Nama:</strong> ${data.pet}</p>
              <p><strong>Ras:</strong> ${data.breed}</p>
              <p><strong>Umur:</strong> ${data.age}</p>
              <p><strong>Berat:</strong> ${data.weight}</p>
            </div>
          </div>
          
          <div>
            <h4 class="font-semibold text-gray-700">Detail Reservasi</h4>
            <p><strong>Layanan:</strong> ${data.service}</p>
            <p><strong>Check-in:</strong> ${data.checkin}</p>
            <p><strong>Check-out:</strong> ${data.checkout}</p>
            <p><strong>Status:</strong> ${data.status}</p>
            <p><strong>Total:</strong> ${data.total}</p>
          </div>
          
          ${data.notes ? `
          <div>
            <h4 class="font-semibold text-gray-700">Catatan Khusus</h4>
            <p class="bg-gray-50 p-3 rounded">${data.notes}</p>
          </div>
          ` : ''}
        </div>
        
        <div class="flex justify-end space-x-2 mt-6">
          <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
            Tutup
          </button>
          <button onclick="editReservation('${data.id}')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Edit Reservasi
          </button>
        </div>
      </div>
    </div>
  `;
  
  document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Edit functionality
function editReservation(reservationId) {
  closeModal();
  
  // Simulasi redirect ke halaman edit
  alert(`Redirect ke halaman edit reservasi ${reservationId}\n\nNanti bisa diganti dengan:\nwindow.location.href = '/owner/reservations/${reservationId}/edit';`);
  
  // Untuk implementasi nyata:
  // window.location.href = `/owner/reservations/${reservationId}/edit`;
}

// Close modal
function closeModal() {
  const modal = document.getElementById('detailModal');
  if (modal) {
    modal.remove();
  }
}

// Tab functionality
function switchTab(tabName) {
  // Hide all tab contents
  const tabContents = document.querySelectorAll('.tab-content');
  tabContents.forEach(content => content.style.display = 'none');
  
  // Remove active class from all tabs
  const tabs = document.querySelectorAll('.tab-link');
  tabs.forEach(tab => {
    tab.classList.remove('border-blue-500', 'text-blue-600');
    tab.classList.add('border-transparent', 'text-gray-500');
  });
  
  // Show selected tab content
  const selectedContent = document.getElementById(`${tabName}-content`);
  if (selectedContent) {
    selectedContent.style.display = 'block';
  }
  
  // Add active class to selected tab
  const selectedTab = document.querySelector(`[data-tab="${tabName}"]`);
  if (selectedTab) {
    selectedTab.classList.add('border-blue-500', 'text-blue-600');
    selectedTab.classList.remove('border-transparent', 'text-gray-500');
  }
}

// Add event listeners for buttons
document.addEventListener('DOMContentLoaded', function() {
  // Add click events to all detail buttons
  const detailButtons = document.querySelectorAll('.btn-detail');
  detailButtons.forEach(button => {
    button.addEventListener('click', function() {
      const reservationId = this.getAttribute('data-id');
      showDetail(reservationId);
    });
  });
  
  // Add click events to all edit buttons
  const editButtons = document.querySelectorAll('.btn-edit');
  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      const reservationId = this.getAttribute('data-id');
      editReservation(reservationId);
    });
  });
});
</script>
@endpush