<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Layanan — Pet Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#FEFBF7] text-[#333333]">

  <!-- Navbar -->
  <nav class="bg-[#FEFBF7] shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold">Pet Hotel</h1>
      <ul class="hidden md:flex items-center space-x-6 font-medium">
        <li><a href="{{ url('/') }}" class="hover:text-[#F2784B]">Beranda</a></li>
        <li><a href="{{ url('/layanan') }}" class="hover:text-[#F2784B]">Layanan</a></li>
        <!-- jika punya halaman fasilitas terpisah boleh tetap ditampilkan: -->
        <!-- <li><a href="{{ url('/fasilitas') }}" class="hover:text-[#F2784B]">Fasilitas</a></li> -->
        <li><a href="{{ url('/tentang') }}" class="hover:text-[#F2784B]">Tentang Kami</a></li>
        <li><a href="{{ url('/kontak') }}" class="hover:text-[#F2784B]">Kontak</a></li>
        <li>
          <a href="{{ url('/reservasi') }}"
             class="inline-block rounded-lg bg-[#F2784B] px-4 py-2 text-white hover:bg-[#e0673d]">
            Reservasi
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- ====== LAYANAN / PAKET ====== -->
  <section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">PAKET LAYANAN KAMI</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
          Pilih paket yang sesuai dengan kebutuhan hewan kesayangan Anda.
          Semua paket dilengkapi dengan perawatan profesional dan kasih sayang tulus.
        </p>
      </div>

      <!-- Cards Basic & Premium (center & equal height) -->
      <div class="flex flex-col lg:flex-row justify-center items-stretch gap-8 mb-10">

        <!-- Basic -->
        <div class="bg-white rounded-lg shadow-lg p-8 w-full lg:w-1/3 flex flex-col">
          <h3 class="text-2xl font-bold mb-4">Basic</h3>
          <ul class="text-left space-y-2 text-gray-600 mb-8 flex-grow">
            <li class="flex items-center">
              <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Kamar Ber-AC
            </li>
            <li class="flex items-center">
              <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Makan 3x sehari
            </li>
            <li class="flex items-center">
              <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Area bermain indoor/outdoor
            </li>
            <li class="flex items-center">
              <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              Laporan harian via WA (foto)
            </li>
          </ul>
          <a href="{{ url('/reservasi') }}" class="mt-auto inline-block text-center rounded-lg bg-[#F2784B] px-4 py-2 text-white hover:bg-[#e0673d]">
            Pilih Paket Basic
          </a>
        </div>

        <!-- Premium -->
        <div class="bg-white rounded-lg shadow-lg p-8 w-full lg:w-1/3 border-2 border-[#F2784B] flex flex-col">
          <h3 class="text-2xl font-bold mb-4 text-[#F2784B]">Premium</h3>
          <ul class="text-left space-y-2 text-gray-600 mb-8 flex-grow">
            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Kamar Ber-AC</li>
            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Makan 3x sehari</li>
            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Area bermain indoor/outdoor</li>
            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Laporan harian via WA + VC</li>
            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Snack & Treats</li>
          </ul>
          <a href="{{ url('/reservasi') }}" class="mt-auto inline-block text-center rounded-lg bg-[#F2784B] px-4 py-2 text-white hover:bg-[#e0673d]">
            Pilih Paket Premium
          </a>
        </div>

      </div>

      <!-- CTA Reservasi -->
      <div class="text-center">
        <a href="{{ url('/reservasi') }}" class="inline-block rounded-lg border border-[#F2784B] px-6 py-3 hover:bg-[#fff0e9]">
          Belum yakin paketnya? Mulai Reservasi dulu
        </a>
      </div>

      <!-- Layanan Tambahan -->
      <div class="mt-16">
        <h3 class="text-2xl font-bold mb-6 text-center">Layanan Tambahan</h3>
        <p class="text-gray-600 mb-12 text-center">
          Tingkatkan pengalaman hewan peliharaan Anda dengan layanan tambahan kami
        </p>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white rounded-lg shadow p-6 text-center">
            <h4 class="font-semibold mb-2">Grooming Premium</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 150.000</p>
            <p class="text-gray-600">Spa lengkap, potong kuku, bersih telinga, aromaterapi</p>
          </div>
          <div class="bg-white rounded-lg shadow p-6 text-center">
            <h4 class="font-semibold mb-2">Boarding Asikk</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 200.000</p>
            <p class="text-gray-600">Penitipan hewan dijamin aman dan nyaman</p>
          </div>
          <div class="bg-white rounded-lg shadow p-6 text-center">
            <h4 class="font-semibold mb-2">Pick-up & Delivery</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
            <p class="text-gray-600">Layanan antar jemput dalam radius 10km</p>
          </div>
          <div class="bg-white rounded-lg shadow p-6 text-center">
            <h4 class="font-semibold mb-2">Kolam Renang</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
            <p class="text-gray-600">Pelatihan kepatuhan dan sosialisasi (per sesi)</p>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Footer mini -->
  <footer class="py-10 text-center text-sm text-gray-500">
    © {{ date('Y') }} Pet Hotel. All rights reserved.
  </footer>

</body>
</html>
