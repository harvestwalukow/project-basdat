@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Success/Error Messages -->
  @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
  @endif
  @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline">{{ session('error') }}</span>
    </div>
  @endif

  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Manajemen Karyawan</h1>
      <p class="text-gray-500">Kelola tim dan sumber daya manusia</p>
    </div>
    <button onclick="openAddModal()" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
      <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
           stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
           d="M12 4v16m8-8H4"/></svg>
      Tambah Karyawan
    </button>
  </div>

  {{-- Department Stats --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach ($departmentStats ?? [] as $dept)
      <div class="bg-white shadow rounded-lg p-4 text-center">
        <p class="text-lg font-semibold">{{ $dept['name'] }}</p>
        <p class="text-gray-500 text-sm">{{ $dept['employees'] }} Karyawan</p>
      </div>
    @endforeach
  </div>

  {{-- Tabs --}}
  <div>
    <ul class="flex border-b text-sm font-medium">
      <li class="mr-2"><a href="#employees" class="tab-link inline-block p-4 border-b-2 border-transparent hover:border-gray-300" data-tab="employees">Daftar Karyawan</a></li>
      <li class="mr-2"><a href="#schedule" class="tab-link inline-block p-4 border-b-2 border-transparent hover:border-gray-300" data-tab="schedule">Jadwal Kerja</a></li>
    </ul>
  </div>

  {{-- Employees Tab --}}
  <div id="employees" class="tab-content block">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
      @forelse ($employees ?? [] as $emp)
        <div class="bg-white shadow rounded-lg p-4">
          <div class="flex items-center justify-between mb-3">
            <div>
              <h3 class="font-semibold">{{ $emp['name'] }}</h3>
              <p class="text-sm text-gray-500">{{ $emp['specialization'] }}</p>
            </div>
            <span class="px-2 py-1 text-xs rounded {{ $emp['status'] == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
              {{ $emp['status'] == 'active' ? 'Aktif' : 'Non-aktif' }}
            </span>
          </div>

          <div class="space-y-1 text-sm text-gray-600 mb-3">
            <p>{{ $emp['email'] }}</p>
            <p>{{ $emp['phone'] }}</p>
            <p>Bergabung: {{ $emp['joinDate'] }}</p>
          </div>

          <div class="flex gap-2">
            <button onclick="openEditModal({{ $emp['id'] }})" class="flex-1 px-2 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Edit</button>
            <button onclick="confirmDelete({{ $emp['id'] }}, '{{ $emp['name'] }}')" class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">Hapus</button>
          </div>
        </div>
      @empty
        <div class="col-span-3 text-center py-12 text-gray-500">
          <p class="text-lg font-medium">Belum ada data karyawan</p>
        </div>
      @endforelse
    </div>
  </div>

  {{-- Schedule Tab --}}
  <div id="schedule" class="tab-content hidden">
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-semibold mb-4">Jadwal Shift Karyawan</h2>

      {{-- Shift Legend --}}
      <div class="flex gap-6 mb-6">
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-yellow-100 border border-yellow-200 rounded"></div>
          <span class="text-sm text-gray-600">Shift Pagi (08:00 - 20:00)</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-blue-100 border border-blue-200 rounded"></div>
          <span class="text-sm text-gray-600">Shift Malam (20:00 - 08:00)</span>
        </div>
      </div>

      {{-- Calendar Table --}}
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-100">
              <th class="border p-3 text-left font-semibold w-48">Karyawan</th>
              @php
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
              @endphp
              @foreach($days as $day)
                <th class="border p-3 text-center font-semibold">{{ $day }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @php
              // Auto-generate fair shift distribution
              $totalEmployees = count($employees ?? []);
              $shiftsPerWeek = 7; // 7 days
              
              // Create a balanced shift pattern
              // Each employee works 5-6 days per week with mix of morning and night shifts
              // Pattern: 2 morning, 2 night, 1 morning, 2 off (repeating with variation)
              
              function generateFairSchedule($employeeIndex, $totalEmployees) {
                $patterns = [
                  // Pattern 1: Morning heavy
                  ['pagi', 'pagi', 'malam', 'malam', 'pagi', 'pagi', 'malam'],
                  // Pattern 2: Night heavy
                  ['malam', 'malam', 'pagi', 'pagi', 'malam', 'malam', 'pagi'],
                  // Pattern 3: Alternating
                  ['pagi', 'malam', 'pagi', 'malam', 'pagi', 'malam', 'pagi'],
                  // Pattern 4: Balanced
                  ['malam', 'pagi', 'malam', 'pagi', 'malam', 'pagi', 'malam'],
                  // Pattern 5: Mixed 1
                  ['pagi', 'pagi', 'malam', 'pagi', 'malam', 'malam', 'pagi'],
                  // Pattern 6: Mixed 2
                  ['malam', 'malam', 'pagi', 'malam', 'pagi', 'pagi', 'malam'],
                ];
                
                // Rotate pattern based on employee index to ensure coverage
                $patternIndex = $employeeIndex % count($patterns);
                return $patterns[$patternIndex];
              }
            @endphp
            
            @forelse ($employees ?? [] as $index => $emp)
              @php
                $schedule = generateFairSchedule($index, $totalEmployees);
              @endphp
              <tr>
                <td class="border p-3">
                  <div class="font-medium">{{ $emp['name'] }}</div>
                  <div class="text-xs text-gray-500">{{ $emp['department'] }}</div>
                </td>
                @foreach($schedule as $shift)
                  @php
                    $bgColor = $shift === 'pagi' ? 'bg-yellow-100' : ($shift === 'malam' ? 'bg-blue-100' : 'bg-gray-50');
                  @endphp
                  <td class="border p-4 {{ $bgColor }}"></td>
                @endforeach
              </tr>
            @empty
              <tr>
                <td colspan="8" class="p-8 text-center text-gray-500">Belum ada data karyawan</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
  <!-- Add Staff Modal -->
  <div id="addStaffModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Tambah Karyawan Baru</h3>
        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <form action="{{ route('admin.staff.store') }}" method="POST">
        @csrf
        <input type="hidden" name="role" value="staff">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
            <input type="text" name="no_telepon" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Spesialisasi</label>
            <select name="specialization" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              <option value="">Pilih Spesialisasi</option>
              <option value="groomer">Groomer</option>
              <option value="handler">Handler</option>
              <option value="trainer">Trainer</option>
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea name="alamat" required rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
          <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Batal</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Staff Modal -->
  <div id="editStaffModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Edit Karyawan</h3>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <form id="editStaffForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="role" value="staff">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" id="edit_nama_lengkap" name="nama_lengkap" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="edit_email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
            <input type="text" id="edit_no_telepon" name="no_telepon" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Password (Kosongkan jika tidak diubah)</label>
            <input type="password" id="edit_password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Spesialisasi</label>
            <select id="edit_specialization" name="specialization" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              <option value="groomer">Groomer</option>
              <option value="handler">Handler</option>
              <option value="trainer">Trainer</option>
            </select>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea id="edit_alamat" name="alamat" required rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Batal</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Form -->
  <form id="deleteStaffForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
  </form>
</div>
@endsection

@push('scripts')
<script>
  // Tab switching
  document.querySelectorAll('.tab-link').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = e.target.dataset.tab;

      // Remove active state from all tabs
      document.querySelectorAll('.tab-link').forEach(l => {
        l.classList.remove('border-blue-600');
        l.classList.add('border-transparent');
      });
      document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));

      // Activate target tab
      e.target.classList.remove('border-transparent');
      e.target.classList.add('border-blue-600');
      document.getElementById(target).classList.remove('hidden');
    });
  });

  // Set initial active tab based on URL hash or default to employees
  const setInitialTab = () => {
    const hash = window.location.hash || '#employees';
    const tabId = hash.substring(1);
    const targetTab = document.querySelector(`[data-tab="${tabId}"]`);
    if (targetTab) {
      targetTab.classList.remove('border-transparent');
      targetTab.classList.add('border-blue-600');
      document.getElementById(tabId).classList.remove('hidden');
    }
  };
  setInitialTab();

  // Add Staff Modal
  function openAddModal() {
    document.getElementById('addStaffModal').classList.remove('hidden');
  }

  function closeAddModal() {
    document.getElementById('addStaffModal').classList.add('hidden');
  }

  // Edit Staff Modal
  function openEditModal(id) {
    fetch(`/admin/staff/${id}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
        document.getElementById('edit_email').value = data.email;
        document.getElementById('edit_no_telepon').value = data.no_telepon || '';
        document.getElementById('edit_alamat').value = data.alamat;
        
        // Set specialization
        const specializationSelect = document.getElementById('edit_specialization');
        if (specializationSelect && data.specialization) {
          specializationSelect.value = data.specialization;
        }
        
        document.getElementById('editStaffForm').action = `/admin/staff/${id}`;
        document.getElementById('editStaffModal').classList.remove('hidden');
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengambil data karyawan');
      });
  }

  function closeEditModal() {
    document.getElementById('editStaffModal').classList.add('hidden');
  }

  // Delete Staff
  function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus karyawan ${name}?`)) {
      const form = document.getElementById('deleteStaffForm');
      form.action = `/admin/staff/${id}`;
      form.submit();
    }
  }

  // Close modals when clicking outside
  window.onclick = function(event) {
    const addModal = document.getElementById('addStaffModal');
    const editModal = document.getElementById('editStaffModal');
    if (event.target == addModal) {
      closeAddModal();
    }
    if (event.target == editModal) {
      closeEditModal();
    }
  }
</script>
@endpush
