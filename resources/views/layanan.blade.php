<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Layanan — Pet Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover bg-center bg-fixed text-[#333333] antialiased"
      style="background-image: url('/img/backround.png');">

  <!-- NAVBAR -->
  <header class="sticky top-0 z-50 bg-[#FEFBF7]/90 backdrop-blur border-b border-orange-100/60">
    <div class="max-w-7xl mx-auto px-6 py-4 grid grid-cols-12 items-center gap-4">
      <a href="{{ url('/') }}" class="col-span-6 md:col-span-3 text-2xl font-extrabold text-[#F2784B]">
        PawsHotel
      </a>
      <nav class="hidden md:flex col-span-6 md:col-span-6 justify-center gap-8 font-medium">
        <a href="{{ url('/') }}" class="hover:text-[#F2784B]">Beranda</a>
        <a href="{{ url('/layanan') }}" class="hover:text-[#F2784B]">Layanan</a>
        <a href="{{ url('/about') }}" class="hover:text-[#F2784B]">Tentang Kami</a>
        <a href="{{ url('/kontak') }}" class="hover:text-[#F2784B]">Kontak</a>
      </nav>
      <div class="col-span-6 md:col-span-3 flex justify-end">
        <a href="{{ url('/reservasi') }}"
           class="inline-block rounded-xl bg-[#F2784B] px-5 py-2.5 text-white font-semibold hover:bg-[#e0673d]">
          Reservasi Sekarang
        </a>
      </div>
    </div>
  </header>

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
            <h4 class="font-semibold mb-2">Kolam Renang</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
            <p class="text-gray-600">Layanan berenang bagi anabul</p>
          </div>
          <div class="bg-white rounded-lg shadow p-6 text-center">
            <h4 class="font-semibold mb-2">Pick-up & Delivery</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 100.000</p>
            <p class="text-gray-600">Layanan antar jemput dalam radius 10km</p>
          </div>
          <div class="bg-white rounded-lg shadow p-6 text-center">
            <h4 class="font-semibold mb-2">Enrichment Extra</h4>
            <p class="text-[#F2784B] font-bold mb-2">Rp 45.000</p>
            <p class="text-gray-600">Sesi stimulasi 15–20 menit (puzzle feeder, lick mat, sniffing)</p>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- FOOTER -->
  <footer id="kontak" class="bg-[#333] text-white py-10">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
      <div>
        <h3 class="text-xl font-bold mb-3">PawsHotel</h3>
        <p class="text-gray-300">Penitipan hewan terpercaya dengan fasilitas lengkap dan staf berpengalaman.</p>
      </div>
      <div>
        <h4 class="font-semibold mb-2">Kontak</h4>
        <p>Email: info@pawshotel.com</p>
        <p>Telp: +62 812-3456-7890</p>
        <p>Alamat: Surabaya, Indonesia</p>
      </div>
      <div>
        <h4 class="font-semibold mb-2">Ikuti Kami</h4>
        <div class="flex gap-4">
          <a href="#" class="hover:text-orange-400">Facebook</a>
          <a href="#" class="hover:text-orange-400">Instagram</a>
          <a href="#" class="hover:text-orange-400">Twitter</a>
        </div>
      </div>
    </div>
    <p class="text-center text-gray-400 mt-6">© 2025 PawsHotel. All rights reserved.</p>
  </footer>

</body>
</html>
