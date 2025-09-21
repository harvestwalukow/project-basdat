<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PawsHotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    html { scroll-behavior: smooth; }
    body { font-family: 'Nunito', sans-serif; }
  </style>
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

 <!-- Hero Section Transparan -->
<section class="relative px-8 py-20">
  <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-10">
    
    <!-- Kiri: Teks -->
    <div class="space-y-6 bg-white/60 p-6 rounded-2xl shadow-lg">
      <h1 class="text-5xl font-extrabold text-gray-900 leading-tight">
        Rumah <br> untuk Sahabat Berbulu Anda
      </h1>
      <p class="text-lg text-gray-700">
        Berikan kenyamanan terbaik untuk hewan kesayangan Anda dengan penitipan yang hangat, area bermain seru, dan update harian yang bikin tenang.
      </p>

      <!-- Tombol -->
      <div class="flex gap-4">
        <a href="#about" class="px-6 py-3 rounded-full bg-orange-400 text-white font-semibold shadow hover:bg-orange-500">
          Daftar Sekarang
        </a>
        <a href="#categories" class="px-6 py-3 rounded-full border border-sky-400 text-sky-500 font-semibold hover:bg-sky-50">
          Lihat Fasilitas →
        </a>
      </div>
    </div>

    <!-- Kanan: Gambar -->
    <div class="flex justify-center">
      <img src="/img/anjing.png" alt="Anjing dan Kucing" 
           class="max-h-[500px] md:max-h-[600px] object-contain drop-shadow-lg">
    </div>

  </div>
</section>



  <!-- TESTIMONI -->
  <section class="py-16 bg-[#FEFBF7]">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Apa Kata Pelanggan Kami</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <div class="flex text-yellow-400 mb-4">★★★★★</div>
          <p class="text-gray-700 mb-4">
            "Kucing saya Mimi terlihat bahagia dan sehat setelah menginap 1 minggu."
          </p>
          <p class="font-semibold">Sarah Wijayanto</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <div class="flex text-yellow-400 mb-4">★★★★</div>
          <p class="text-gray-700 mb-4">
            "Area bermainnya luas, staf ramah, dan komunikatif."
          </p>
          <p class="font-semibold">Rama Putra</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <div class="flex text-yellow-400 mb-4">★★★★★</div>
          <p class="text-gray-700 mb-4">
            "Update hariannya bikin tenang. Sangat direkomendasikan!"
          </p>
          <p class="font-semibold">Laras Anindya</p>
        </div>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="flex justify-center space-x-16 py-10">
    <div class="text-center">
      <p class="text-4xl font-bold">24/7</p>
      <p class="text-gray-600">Perawatan</p>
    </div>
    <div class="text-center">
      <p class="text-4xl font-bold">500+</p>
      <p class="text-gray-600">Pet Senang</p>
    </div>
    <div class="text-center">
      <p class="text-4xl font-bold flex items-center">5
        <svg class="w-8 h-8 text-yellow-400 ml-1" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.368-2.448a1 1 0 00-1.175 0l-3.368 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/>
        </svg>
      </p>
      <p class="text-gray-600">Rating</p>
    </div>
  </section>

  <section id="fasilitas" class="scroll-mt-24 py-20 bg-[#FFF7F2]">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h2 class="text-4xl font-bold mb-3">Fasilitas Kami</h2>
      <p class="text-gray-600 max-w-3xl mx-auto mb-12">
        Semua yang dibutuhkan agar hewan peliharaan merasa aman, nyaman, dan bahagia selama menginap.
      </p>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
          <div class="text-3xl mb-2">❄️</div>
          <h3 class="text-xl font-semibold mb-1">Kamar Ber-AC</h3>
          <p class="text-gray-600">Suhu ruangan stabil untuk istirahat yang nyaman.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
          <div class="text-3xl mb-2">📹</div>
          <h3 class="text-xl font-semibold mb-1">CCTV 24/7</h3>
          <p class="text-gray-600">Pengawasan nonstop; bisa minta video update.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
          <div class="text-3xl mb-2">🎾</div>
          <h3 class="text-xl font-semibold mb-1">Area Bermain</h3>
          <p class="text-gray-600">Indoor/outdoor dengan permainan & obstacle.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
          <div class="text-3xl mb-2">🍴</div>
          <h3 class="text-xl font-semibold mb-1">Dapur Higienis</h3>
          <p class="text-gray-600">Menu bergizi & bisa disesuaikan diet.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
          <div class="text-3xl mb-2">🧼</div>
          <h3 class="text-xl font-semibold mb-1">Sterilisasi Rutin</h3>
          <p class="text-gray-600">Kandang & mainan disanitasi berkala.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
          <div class="text-3xl mb-2">📱</div>
          <h3 class="text-xl font-semibold mb-1">Update Harian</h3>
          <p class="text-gray-600">Foto/video via WA; opsi VC sesuai paket.</p>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
