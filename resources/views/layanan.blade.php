@extends('layouts.app')

@section('title', 'Layanan — PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/backround.png\');"')

@section('content')
  <!-- ====== LAYANAN / PAKET ====== -->
  <section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Header -->
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4 text-gray-800">🐾 Paket Layanan PawsHotel</h2>
        <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed">
          Pilih paket yang sesuai dengan kebutuhan hewan kesayangan Anda.
          Semua paket dilengkapi dengan perawatan profesional dan kasih sayang tulus.
        </p>
      </div>

      <!-- Paket Cards -->
      <div class="flex flex-col lg:flex-row justify-center items-stretch gap-8 mb-10">

        <!-- Basic -->
        <div
          class="bg-white rounded-2xl shadow-lg p-8 w-full lg:w-1/3 border-2 border-[#F2784B] flex flex-col
                 transition-all duration-300 ease-out hover:-translate-y-2 hover:shadow-[0_25px_60px_-10px_rgba(242,120,75,0.4)]">
          <h3 class="text-2xl font-bold mb-2 text-gray-800">Basic</h3>
          <p class="text-[#F2784B] font-bold mb-4 text-lg">Rp 150.000</p>
          <ul class="text-left space-y-2 text-gray-600 mb-8 flex-grow">
            <li class="flex items-center gap-2"><span>🐶</span> Kamar Ber-AC</li>
            <li class="flex items-center gap-2"><span>🍖</span> Makan 3x sehari</li>
            <li class="flex items-center gap-2"><span>🏡</span> Area bermain indoor/outdoor</li>
            <li class="flex items-center gap-2"><span>📸</span> Laporan harian via WA (foto)</li>
          </ul>
          <a href="{{ url('/reservasi') }}" 
             class="mt-auto inline-block text-center rounded-lg bg-[#F2784B] px-5 py-2.5 text-white font-medium tracking-wide transition duration-300 hover:bg-[#e0673d] hover:scale-[1.03]">
            Pilih Paket Basic
          </a>
        </div>

        <!-- Premium -->
        <div
          class="bg-white rounded-2xl shadow-lg p-8 w-full lg:w-1/3 border-2 border-[#F2784B] flex flex-col
                 transition-all duration-300 ease-out hover:-translate-y-2 hover:shadow-[0_25px_60px_-10px_rgba(242,120,75,0.4)]">
          <h3 class="text-2xl font-bold mb-2 text-gray-800">Premium</h3>
          <p class="text-[#F2784B] font-bold mb-4 text-lg">Rp 250.000</p>
          <ul class="text-left space-y-2 text-gray-600 mb-8 flex-grow">
            <li class="flex items-center gap-2"><span>🐶</span> Kamar Ber-AC</li>
            <li class="flex items-center gap-2"><span>🍖</span> Makan 3x sehari</li>
            <li class="flex items-center gap-2"><span>🏡</span> Area bermain indoor/outdoor</li>
            <li class="flex items-center gap-2"><span>📹</span> Laporan harian via WA + VC</li>
            <li class="flex items-center gap-2"><span>🦴</span> Snack & Treats</li>
          </ul>
          <a href="{{ url('/reservasi') }}" 
             class="mt-auto inline-block text-center rounded-lg bg-[#F2784B] px-5 py-2.5 text-white font-medium tracking-wide transition duration-300 hover:bg-[#e0673d] hover:scale-[1.03]">
            Pilih Paket Premium
          </a>
        </div>

      </div>

      <!-- Layanan Tambahan -->
      <div class="mt-20">
        <h3 class="text-2xl font-bold mb-6 text-center text-gray-800">✨ Layanan Tambahan</h3>
        <p class="text-gray-600 mb-12 text-center max-w-2xl mx-auto">
          Tingkatkan pengalaman hewan peliharaan Anda dengan layanan tambahan kami
        </p>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          
          <div class="bg-white rounded-xl shadow p-6 text-center transition duration-300 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)]">
            <h4 class="font-semibold mb-2 text-gray-800">Grooming Premium</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 150.000</p>
            <p class="text-gray-600 text-sm leading-relaxed">Spa lengkap, potong kuku, bersih telinga, aromaterapi</p>
          </div>

          <div class="bg-white rounded-xl shadow p-6 text-center transition duration-300 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)]">
            <h4 class="font-semibold mb-2 text-gray-800">Kolam Renang</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
            <p class="text-gray-600 text-sm leading-relaxed">Layanan berenang bagi anabul</p>
          </div>

          <div class="bg-white rounded-xl shadow p-6 text-center transition duration-300 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)]">
            <h4 class="font-semibold mb-2 text-gray-800">Pick-up & Delivery</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
            <p class="text-gray-600 text-sm leading-relaxed">Layanan antar jemput dalam radius 10km</p>
          </div>

          <div class="bg-white rounded-xl shadow p-6 text-center transition duration-300 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)]">
            <h4 class="font-semibold mb-2 text-gray-800">Enrichment Extra</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 45.000</p>
            <p class="text-gray-600 text-sm leading-relaxed">Sesi stimulasi 15–20 menit (puzzle feeder, lick mat, sniffing)</p>
          </div>

        </div>
      </div>

    </div>
  </section>
@endsection
