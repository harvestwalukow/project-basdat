@extends('owner.layouts.app')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div>
    <h1 class="text-2xl font-bold">Manajemen Hewan Titipan</h1>
    <p class="text-gray-500">Monitor dan kelola anjing & kucing yang sedang dititipkan</p>
  </div>

  <!-- Okupansi Kandang -->
  <div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-lg font-semibold">Okupansi Kandang</h2>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="font-medium">Basic</span>
            <span class="text-sm text-gray-500">18/25</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full" style="width: 72%"></div>
          </div>
          <div class="flex justify-between text-xs text-gray-500">
            <span>Terisi: 18</span>
            <span>Tersedia: 7</span>
          </div>
        </div>

        <!-- Premium -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="font-medium">Premium</span>
            <span class="text-sm text-gray-500">12/15</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-purple-600 h-2 rounded-full" style="width: 80%"></div>
          </div>
          <div class="flex justify-between text-xs text-gray-500">
            <span>Terisi: 12</span>
            <span>Tersedia: 3</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik Hari Ini -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Check-in Hari Ini -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700">Check-in Hari Ini</h3>
        <div class="p-2 bg-blue-100 rounded-lg">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-blue-600 mb-2">5</div>
      <p class="text-sm text-gray-500 mb-4">Total hewan check-in hari ini</p>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Anjing</span>
          <span class="font-medium">3</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Kucing</span>
          <span class="font-medium">2</span>
        </div>
      </div>
    </div>

    <!-- Check-out Hari Ini -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700">Check-out Hari Ini</h3>
        <div class="p-2 bg-green-100 rounded-lg">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-green-600 mb-2">3</div>
      <p class="text-sm text-gray-500 mb-4">Total hewan check-out hari ini</p>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Anjing</span>
          <span class="font-medium">2</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Kucing</span>
          <span class="font-medium">1</span>
        </div>
      </div>
    </div>

    <!-- Total Sedang Dititipkan -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700">Sedang Dititipkan</h3>
        <div class="p-2 bg-purple-100 rounded-lg">
          <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-purple-600 mb-2">30</div>
      <p class="text-sm text-gray-500 mb-4">Total hewan saat ini</p>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Anjing</span>
          <span class="font-medium">18</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Kucing</span>
          <span class="font-medium">12</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Jenis Hewan yang Dititipkan Hari Ini -->
  <div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-lg font-semibold">Jenis Hewan yang Dititipkan Hari Ini</h2>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Anjing -->
        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4 mb-4">
            <div class="p-4 bg-amber-100 rounded-lg">
              <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-2xl text-amber-600">18</h4>
              <p class="text-sm text-gray-600">Anjing</p>
            </div>
          </div>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">Golden Retriever</span>
              <span class="font-medium">5</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">Labrador</span>
              <span class="font-medium">4</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">Bulldog</span>
              <span class="font-medium">3</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">Pomeranian</span>
              <span class="font-medium">2</span>
            </div>
            <div class="flex justify-between items-center py-1">
              <span class="text-gray-600">Lainnya</span>
              <span class="font-medium">4</span>
            </div>
          </div>
        </div>

        <!-- Kucing -->
        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4 mb-4">
            <div class="p-4 bg-pink-100 rounded-lg">
              <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-2xl text-pink-600">12</h4>
              <p class="text-sm text-gray-600">Kucing</p>
            </div>
          </div>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">Persian</span>
              <span class="font-medium">4</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">Maine Coon</span>
              <span class="font-medium">3</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">Siamese</span>
              <span class="font-medium">2</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-gray-100">
              <span class="text-gray-600">British Shorthair</span>
              <span class="font-medium">2</span>
            </div>
            <div class="flex justify-between items-center py-1">
              <span class="text-gray-600">Lainnya</span>
              <span class="font-medium">1</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Detail Check-in dan Check-out Hari Ini -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Check-in List -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          Daftar Check-in Hari Ini
        </h2>
      </div>
      <div class="p-6 space-y-3">
        <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium">Buddy - Golden Retriever</p>
              <p class="text-sm text-gray-600">Pemilik: Sarah Johnson</p>
              <p class="text-xs text-gray-500 mt-1">09:15 AM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700 font-medium">Premium</span>
          </div>
        </div>
        <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium">Luna - Persian Cat</p>
              <p class="text-sm text-gray-600">Pemilik: Michael Chen</p>
              <p class="text-xs text-gray-500 mt-1">10:30 AM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700 font-medium">Basic</span>
          </div>
        </div>
        <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium">Rocky - Labrador</p>
              <p class="text-sm text-gray-600">Pemilik: Amanda Lee</p>
              <p class="text-xs text-gray-500 mt-1">11:45 AM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700 font-medium">Premium</span>
          </div>
        </div>
        <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium">Milo - Siamese Cat</p>
              <p class="text-sm text-gray-600">Pemilik: David Kim</p>
              <p class="text-xs text-gray-500 mt-1">02:20 PM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700 font-medium">Basic</span>
          </div>
        </div>
        <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium">Max - Bulldog</p>
              <p class="text-sm text-gray-600">Pemilik: Jessica Wong</p>
              <p class="text-xs text-gray-500 mt-1">03:00 PM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700 font-medium">Premium</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Check-out List -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold flex items-center gap-2">
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Daftar Check-out Hari Ini
        </h2>
      </div>
      <div class="p-6 space-y-3">
        <div class="border-l-4 border-green-500 bg-green-50 p-3 rounded">
          <div class="flex justify-between items-start mb-2">
            <div>
              <p class="font-medium">Charlie - Golden Retriever</p>
              <p class="text-sm text-gray-600">Pemilik: Robert Johnson</p>
              <p class="text-xs text-gray-500 mt-1">08:30 AM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700 font-medium">Basic</span>
          </div>
          <span class="inline-block px-2 py-0.5 text-xs rounded bg-green-600 text-white">Selesai</span>
        </div>
        <div class="border-l-4 border-green-500 bg-green-50 p-3 rounded">
          <div class="flex justify-between items-start mb-2">
            <div>
              <p class="font-medium">Bella - Maine Coon</p>
              <p class="text-sm text-gray-600">Pemilik: Emma Davis</p>
              <p class="text-xs text-gray-500 mt-1">01:15 PM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700 font-medium">Premium</span>
          </div>
          <span class="inline-block px-2 py-0.5 text-xs rounded bg-green-600 text-white">Selesai</span>
        </div>
        <div class="border-l-4 border-yellow-500 bg-yellow-50 p-3 rounded">
          <div class="flex justify-between items-start mb-2">
            <div>
              <p class="font-medium">Cooper - Labrador</p>
              <p class="text-sm text-gray-600">Pemilik: William Brown</p>
              <p class="text-xs text-gray-500 mt-1">04:00 PM</p>
            </div>
            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700 font-medium">Premium</span>
          </div>
          <span class="inline-block px-2 py-0.5 text-xs rounded bg-yellow-600 text-white">Menunggu Penjemputan</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection