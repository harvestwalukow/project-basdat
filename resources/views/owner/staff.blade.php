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
    @php
      $departmentStats = [
        ["name" => "Perawatan", "employees" => 6, "avgRating" => 4.6, "budget" => 35000000],
        ["name" => "Medis", "employees" => 2, "avgRating" => 4.9, "budget" => 25000000],
        ["name" => "Grooming", "employees" => 4, "avgRating" => 4.7, "budget" => 22000000],
        ["name" => "Training", "employees" => 2, "avgRating" => 4.5, "budget" => 14000000],
        ["name" => "Front Office", "employees" => 3, "avgRating" => 4.4, "budget" => 15000000],
      ];
    @endphp

    @foreach ($departmentStats as $dept)
      <div class="bg-white shadow rounded-lg p-4 text-center space-y-2">
        <h3 class="font-semibold text-sm">{{ $dept['name'] }}</h3>
        <div class="text-2xl font-bold">{{ $dept['employees'] }}</div>
        <div class="flex items-center justify-center gap-1">
          ⭐ <span class="text-xs">{{ $dept['avgRating'] }}</span>
        </div>
        <p class="text-xs text-gray-500">Budget: Rp {{ number_format($dept['budget']/1000000, 0) }}M</p>
      </div>
    @endforeach
  </div>

  {{-- Tabs --}}
  <div>
    <ul class="flex border-b text-sm font-medium">
      <li class="mr-2"><a href="#employees" class="inline-block p-4 border-b-2 border-blue-600">Daftar Karyawan</a></li>
      <li class="mr-2"><a href="#schedule" class="inline-block p-4">Jadwal Kerja</a></li>
      <li class="mr-2"><a href="#performance" class="inline-block p-4">Performance</a></li>
      <li class="mr-2"><a href="#payroll" class="inline-block p-4">Payroll</a></li>
    </ul>
  </div>

  {{-- Employees Grid --}}
  <div id="employees" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @php
      $employees = [
        ["id"=>"EMP001","name"=>"Dr. Amanda Sari","position"=>"Dokter Hewan","department"=>"Medis","email"=>"amanda@pethotel.com","phone"=>"081234567890","joinDate"=>"2022-01-15","status"=>"active","shift"=>"Pagi (08:00-16:00)","rating"=>4.9,"specialization"=>"Penyakit Dalam","experience"=>"8 tahun","salary"=>12000000],
        ["id"=>"EMP002","name"=>"Budi Santoso","position"=>"Pet Caretaker Senior","department"=>"Perawatan","email"=>"budi@pethotel.com","phone"=>"081298765432","joinDate"=>"2021-03-10","status"=>"active","shift"=>"Pagi (08:00-16:00)","rating"=>4.7,"specialization"=>"Anjing Besar","experience"=>"5 tahun","salary"=>6500000],
        ["id"=>"EMP003","name"=>"Siti Nurhaliza","position"=>"Groomer","department"=>"Grooming","email"=>"siti@pethotel.com","phone"=>"081356789012","joinDate"=>"2022-08-20","status"=>"active","shift"=>"Siang (12:00-20:00)","rating"=>4.8,"specialization"=>"Cat Grooming","experience"=>"3 tahun","salary"=>5500000],
      ];
    @endphp

    @foreach ($employees as $emp)
      <div class="bg-white shadow rounded-lg p-4">
        <div class="flex items-center justify-between mb-3">
          <div>
            <h3 class="font-semibold">{{ $emp['name'] }}</h3>
            <p class="text-sm text-gray-500">{{ $emp['position'] }}</p>
          </div>
          <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Aktif</span>
        </div>

        <div class="flex justify-between items-center mb-2">
          <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">{{ $emp['department'] }}</span>
          <span class="flex items-center text-yellow-500">⭐ {{ $emp['rating'] }}</span>
        </div>

        <p class="text-sm text-gray-600">📧 {{ $emp['email'] }}</p>
        <p class="text-sm text-gray-600">📞 {{ $emp['phone'] }}</p>
        <p class="text-sm text-gray-600">🕒 {{ $emp['shift'] }}</p>

        <div class="bg-gray-100 p-2 rounded mt-3 text-sm space-y-1">
          <p><b>Spesialisasi:</b> {{ $emp['specialization'] }}</p>
          <p><b>Pengalaman:</b> {{ $emp['experience'] }}</p>
          <p><b>Bergabung:</b> {{ $emp['joinDate'] }}</p>
        </div>

        <div class="flex gap-2 pt-2">
          <button class="flex-1 px-2 py-1 border rounded text-sm">👁 Profile</button>
          <button class="flex-1 px-2 py-1 border rounded text-sm">✏️ Edit</button>
          <button class="px-2 py-1 border rounded text-sm">📞</button>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
