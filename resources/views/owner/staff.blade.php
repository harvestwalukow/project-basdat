@extends('owner.layouts.app')

@section('content')
<div class="space-y-6 p-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Manajemen Karyawan</h1>
      <p class="text-gray-500">Kelola tim dan sumber daya manusia</p>
    </div>
    <button class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
      <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
           stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
           d="M12 4v16m8-8H4"/></svg>
      Tambah Karyawan
    </button>
  </div>

  {{-- Department Stats --}}
  <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
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
      <li class="mr-2"><a href="#employees" class="tab-link inline-block p-4 border-b-2 border-blue-600" data-tab="employees">Daftar Karyawan</a></li>
      <li class="mr-2"><a href="#schedule" class="tab-link inline-block p-4" data-tab="schedule">Jadwal Kerja</a></li>
      <li class="mr-2"><a href="#payroll" class="tab-link inline-block p-4" data-tab="payroll">Payroll</a></li>
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
              <p class="text-sm text-gray-500">{{ $emp['position'] }}</p>
            </div>
            <span class="px-2 py-1 text-xs rounded {{ $emp['status'] == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
              {{ $emp['status'] == 'active' ? 'Aktif' : 'Non-aktif' }}
            </span>
          </div>

          <div class="flex justify-between items-center mb-2">
            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">{{ $emp['department'] }}</span>
            <span class="flex items-center text-gray-400">★ {{ number_format($emp['rating'] ?? 0, 1) }}</span>
          </div>

          <p class="text-sm text-gray-600">{{ $emp['email'] }}</p>
          <p class="text-sm text-gray-600">{{ $emp['phone'] }}</p>
          <p class="text-sm text-gray-600">{{ $emp['shift'] }}</p>

          <div class="bg-gray-100 p-2 rounded mt-3 text-sm space-y-1">
            <p><b>Spesialisasi:</b> {{ $emp['specialization'] }}</p>
            <p><b>Pengalaman:</b> {{ $emp['experience'] }}</p>
            <p><b>Bergabung:</b> {{ $emp['joinDate'] }}</p>
          </div>

          <div class="flex gap-2 pt-2">
            <button class="flex-1 px-2 py-1 border rounded text-sm hover:bg-gray-50">Edit</button>
            <button class="px-2 py-1 border rounded text-sm hover:bg-gray-50">Detail</button>
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
    <div class="bg-white shadow rounded-lg p-6 text-center">
      <p class="text-gray-500">Jadwal Kerja Coming Soon...</p>
    </div>
  </div>

  {{-- Payroll Tab --}}
  <div id="payroll" class="tab-content hidden">
    <h2 class="text-xl font-semibold mb-4">Ringkasan Payroll</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white shadow rounded-lg p-4 text-center">
        <p class="text-green-600 text-2xl font-bold">Rp {{ number_format(($totalPayroll ?? 0)/1000000, 1) }}M</p>
        <p class="text-gray-500 text-sm">Total Payroll Bulan Ini</p>
      </div>
      <div class="bg-white shadow rounded-lg p-4 text-center">
        <p class="text-blue-600 text-2xl font-bold">{{ $totalEmployees ?? 0 }}</p>
        <p class="text-gray-500 text-sm">Total Karyawan Aktif</p>
      </div>
      <div class="bg-white shadow rounded-lg p-4 text-center">
        <p class="text-purple-600 text-2xl font-bold">Rp {{ number_format(($avgSalary ?? 0)/1000000, 1) }}M</p>
        <p class="text-gray-500 text-sm">Rata-rata Gaji</p>
      </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Posisi</th>
            <th class="p-3 text-left">Departemen</th>
            <th class="p-3 text-left">Gaji Pokok</th>
            <th class="p-3 text-left">Bonus</th>
            <th class="p-3 text-left">Total</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($employees ?? [] as $emp)
            <tr class="border-t">
              <td class="p-3 font-medium">{{ $emp['name'] }}</td>
              <td class="p-3">{{ $emp['position'] }}</td>
              <td class="p-3"><span class="px-2 py-1 rounded text-xs bg-gray-100">{{ $emp['department'] }}</span></td>
              <td class="p-3">Rp {{ number_format($emp['salary'] ?? 0, 0, ',', '.') }}</td>
              <td class="p-3">Rp {{ number_format($emp['bonus'] ?? 0, 0, ',', '.') }}</td>
              <td class="p-3 font-bold text-gray-800">Rp {{ number_format(($emp['salary'] ?? 0) + ($emp['bonus'] ?? 0), 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="p-8 text-center text-gray-500">Belum ada data payroll</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- JS Tab Switcher --}}
<script>
  document.querySelectorAll('.tab-link').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = e.target.dataset.tab;

      // Remove active state
      document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('border-blue-600'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));

      // Activate target
      e.target.classList.add('border-blue-600');
      document.getElementById(target).classList.remove('hidden');
    });
  });
</script>
@endsection