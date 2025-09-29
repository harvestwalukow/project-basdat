@extends('owner.layouts.app')

@section('content')
<div class="p-6">
  <h1 class="text-2xl font-bold mb-6">Daftar Hewan</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <!-- Pet 1 -->
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="font-bold text-lg">Buddy</h3>
      <p class="text-sm text-gray-500">Golden Retriever • 3 tahun</p>
      <p class="mt-2 text-sm">Pemilik: <span class="font-medium">Sarah Johnson</span></p>
      <p class="text-sm">Kandang: Premium A-12</p>
      <p class="text-sm">Check-out: 2024-01-20</p>
      <span class="inline-block mt-2 px-2 py-1 text-xs rounded bg-green-100 text-green-800">
        Healthy
      </span>
    </div>

    <!-- Pet 2 -->
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="font-bold text-lg">Whiskers</h3>
      <p class="text-sm text-gray-500">Persian Cat • 2 tahun</p>
      <p class="mt-2 text-sm">Pemilik: <span class="font-medium">Michael Chen</span></p>
      <p class="text-sm">Kandang: Standard B-05</p>
      <p class="text-sm">Check-out: 2024-01-18</p>
      <span class="inline-block mt-2 px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">
        Monitoring
      </span>
    </div>

    <!-- Pet 3 -->
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="font-bold text-lg">Coco</h3>
      <p class="text-sm text-gray-500">Parrot • 5 tahun</p>
      <p class="mt-2 text-sm">Pemilik: <span class="font-medium">Amelia Wong</span></p>
      <p class="text-sm">Kandang: Deluxe C-07</p>
      <p class="text-sm">Check-out: 2024-01-25</p>
      <span class="inline-block mt-2 px-2 py-1 text-xs rounded bg-green-100 text-green-800">
        Healthy
      </span>
    </div>
  </div>
</div>
@endsection
