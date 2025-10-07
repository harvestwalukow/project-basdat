@extends('layouts.app')

@section('title', 'Layanan — PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/backround.png\');"')

@section('content')
  <!-- ====== LAYANAN / PAKET ====== -->
  <section class="py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Header -->
      <div class="text-center mb-16 fade-in">
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
          class="package-card bg-[#ffedd3]/90 backdrop-blur-sm rounded-2xl shadow-lg p-8 w-full lg:w-1/3 border border-[#F2784B]/30 flex flex-col
                 transition-all duration-500 ease-out hover:-translate-y-2 hover:shadow-[0_25px_60px_-10px_rgba(242,120,75,0.4)] opacity-0 translate-y-5">
          <h3 class="text-2xl font-bold mb-2 text-gray-800">Basic</h3>
          <p class="text-[#F2784B] font-bold mb-2 text-lg transition-transform duration-300">Rp 150.000</p>
          <p class="text-gray-500 text-sm mb-4">Ideal untuk hewan peliharaan kecil atau masa inap singkat.</p>
          <ul class="text-left space-y-2 text-black-600 mb-4 flex-grow">
            <li class="flex items-center gap-2"><span>🐶</span> Kamar Ber-AC</li>
            <li class="flex items-center gap-2"><span>🍖</span> Makan 3x sehari</li>
            <li class="flex items-center gap-2"><span>🏡</span> Area bermain indoor/outdoor</li>
            <li class="flex items-center gap-2"><span>📸</span> Laporan harian via WA (foto)</li>
          </ul>
        </div>

        <!-- Premium -->
        <div
          class="package-card bg-[#ffedd3]/90 backdrop-blur-sm rounded-2xl shadow-lg p-8 w-full lg:w-1/3 border border-[#F2784B]/30 flex flex-col
                 transition-all duration-500 ease-out hover:-translate-y-2 hover:shadow-[0_25px_60px_-10px_rgba(242,120,75,0.4)] opacity-0 translate-y-5">
          <h3 class="text-2xl font-bold mb-2 text-gray-800">Premium</h3>
          <p class="text-[#F2784B] font-bold mb-2 text-lg transition-transform duration-300">Rp 250.000</p>
          <p class="text-gray-500 text-sm mb-4">Untuk pengalaman menginap terbaik dan update langsung dari staf kami.</p>
          <ul class="text-left space-y-2 text-black-600 mb-4 flex-grow">
            <li class="flex items-center gap-2"><span>🐶</span> Kamar Ber-AC</li>
            <li class="flex items-center gap-2"><span>🍖</span> Makan 3x sehari</li>
            <li class="flex items-center gap-2"><span>🏡</span> Area bermain indoor/outdoor</li>
            <li class="flex items-center gap-2"><span>📹</span> Laporan harian via WA + VC</li>
            <li class="flex items-center gap-2"><span>🦴</span> Snack & Treats</li>
          </ul>
        </div>

      </div>

      <!-- Layanan Tambahan -->
      <div class="mt-20 fade-in">
        <h3 class="text-2xl font-bold mb-6 text-center text-gray-800">✨ Layanan Tambahan  ✨</h3>
        <p class="text-gray-600 mb-12 text-center max-w-2xl mx-auto">
          Tingkatkan pengalaman hewan peliharaan Anda dengan layanan tambahan kami
        </p>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          
          <!-- Grooming -->
          <div class="extra-card bg-[#fdd190]/90 backdrop-blur-sm border border-[#F2784B]/20 rounded-2xl shadow overflow-hidden text-center transition duration-500 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)] opacity-0 translate-y-5">
            <img src="/img/grooming.jpg" alt="Grooming Premium" class="w-full h-40 object-cover">
            <div class="p-6">
              <h4 class="font-semibold mb-2 text-gray-800">🛁 Grooming Premium</h4>
              <p class="text-[#F2784B] font-bold mb-2">Rp 150.000</p>
              <p class="text-gray-600 text-sm leading-relaxed">Spa lengkap, potong kuku, bersih telinga, aromaterapi</p>
            </div>
          </div>

          <!-- Kolam Renang -->
          <div class="extra-card bg-[#fdd190]/90 backdrop-blur-sm border border-[#F2784B]/20 rounded-2xl shadow overflow-hidden text-center transition duration-500 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)] opacity-0 translate-y-5">
            <img src="/img/pool.jpg" alt="Kolam Renang" class="w-full h-40 object-cover">
            <div class="p-6">
              <h4 class="font-semibold mb-2 text-gray-800">🏊 Kolam Renang</h4>
              <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
              <p class="text-gray-600 text-sm leading-relaxed">Layanan berenang bagi anabul</p>
            </div>
          </div>

          <!-- Pick-up -->
          <div class="extra-card bg-[#fdd190]/90 backdrop-blur-sm border border-[#F2784B]/20 rounded-2xl shadow overflow-hidden text-center transition duration-500 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)] opacity-0 translate-y-5">
            <img src="/img/pickup.jpg" alt="Pick-up & Delivery" class="w-full h-40 object-cover">
            <div class="p-6">
              <h4 class="font-semibold mb-2 text-gray-800">🚗 Pick-up & Delivery</h4>
              <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
              <p class="text-gray-600 text-sm leading-relaxed">Layanan antar jemput dalam radius 10km</p>
            </div>
          </div>

          <!-- Enrichment -->
          <div class="extra-card bg-[#fdd190]/90 backdrop-blur-sm border border-[#F2784B]/20 rounded-2xl shadow overflow-hidden text-center transition duration-500 ease-out 
                      hover:-translate-y-2 hover:shadow-[0_15px_35px_-10px_rgba(242,120,75,0.4)] opacity-0 translate-y-5">
            <img src="/img/enrichment.jpg" alt="Enrichment Extra" class="w-full h-40 object-cover">
            <div class="p-6">
              <h4 class="font-semibold mb-2 text-gray-800">🎯 Enrichment Extra</h4>
              <p class="text-[#F2784B] font-bold mb-2">Rp 45.000</p>
              <p class="text-gray-600 text-sm leading-relaxed">Sesi stimulasi 15–20 menit (puzzle feeder, lick mat, sniffing)</p>
            </div>
          </div>

        </div>

        <!-- CTA bawah -->
        <div class="text-center mt-16 fade-in">
          <a href="{{ route('reservasi') }}"
             class="inline-block bg-[#F2784B] text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-[#e0673d] transition">
            Reservasi Sekarang 🐾
          </a>
          <p class="text-gray-600 mt-3 text-sm">Pesan tempat untuk hewan kesayangan Anda hari ini!</p>
        </div>
      </div>

    </div>
  </section>

  @push('scripts')
  <script>
    // Animasi Fade-in Saat Scroll
    const fadeTargets = document.querySelectorAll('.fade-in, .package-card, .extra-card');
    const observer = new IntersectionObserver(entries => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.classList.add('opacity-100', 'translate-y-0');
          }, index * 150);
        }
      });
    }, { threshold: 0.2 });

    fadeTargets.forEach(el => observer.observe(el));
  </script>
  @endpush
@endsection
