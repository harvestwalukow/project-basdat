<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PawsHotel - Fasilitas</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FEFBF7] text-[#333]">

  <!-- Navbar -->
  <nav class="bg-[#FEFBF7] shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold">Pet Hotel</h1>
      <ul class="hidden md:flex space-x-6 font-medium">
         <li><a href="{{ url('/') }}" class="hover:text-[#F2784B]">Beranda</a></li>
         <li><a href="{{ url('/layanan') }}" class="hover:text-[#F2784B]">Layanan</a></li>
         <li><a href="{{ url('/fasilitas') }}" class="hover:text-[#F2784B]">Fasilitas</a></li>
         <li><a href="{{ url('/tentang') }}" class="hover:text-[#F2784B]">Tentang Kami</a></li>
         <li><a href="{{ url('/kontak') }}" class="hover:text-[#F2784B]">Kontak</a></li>
      </ul>
    </div>
  </nav>

  <!-- Section Fasilitas -->
  <section class="py-16 px-6 max-w-7xl mx-auto">
    <h2 class="text-3xl text-[#F2784B] text-center font-bold mb-4">Fasilitas Modern & Lengkap</h2>
    <p class="text-center text-gray-600 text-lg mb-12">
      Dilengkapi dengan fasilitas terdepan untuk memberikan pengalaman terbaik bagi hewan peliharaan Anda
    </p>
    
    <div class="grid lg:grid-cols-3 gap-8">
      <!-- Card Ruangan Ber-AC -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">❄️</div>
        <h3 class="text-xl font-semibold mb-2">Ruangan Ber-AC</h3>
        <p class="text-gray-600">Semua kamar dilengkapi AC untuk kenyamanan optimal</p>
      </div>

      <!-- Card CCTV -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">📹</div>
        <h3 class="text-xl font-semibold mb-2">CCTV 24/7</h3>
        <p class="text-gray-600">Pengawasan keamanan dan live streaming untuk pemilik</p>
      </div>

      <!-- Card Area Bermain -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🎮</div>
        <h3 class="text-xl font-semibold mb-2">Area Bermain</h3>
        <p class="text-gray-600">Taman luas dengan berbagai permainan dan obstacle</p>
      </div>

      <!-- Card Klinik -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">⚕️</div>
        <h3 class="text-xl font-semibold mb-2">Klinik In-House</h3>
        <p class="text-gray-600">Dokter hewan siaga 24 jam untuk emergency</p>
      </div>

      <!-- Card Kitchen -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🍴</div>
        <h3 class="text-xl font-semibold mb-2">Kitchen Premium</h3>
        <p class="text-gray-600">Menu khusus bergizi untuk hewan kesayangan</p>
      </div>

      <!-- Card Wifi -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">📶</div>
        <h3 class="text-xl font-semibold mb-2">WiFi & Apps</h3>
        <p class="text-gray-600">Koneksi internet dan aplikasi monitoring untuk pemilik</p>
      </div>
    </div>

    <!-- Gambar + Why -->
    <div class="grid lg:grid-cols-2 gap-8 mt-12">
      <div>
        <img class="rounded-lg shadow-lg" src="https://images.unsplash.com/photo-1619983081634-3e16f3a83692?ixlib=rb-4.0.3&q=80&w=1080" alt="Cat on sofa">
      </div>
      <div class="bg-[#fff7ef] p-6 rounded-lg shadow">
        <h3 class="text-2xl text-[#F2784B] font-bold mb-4">Mengapa Memilih PawsHotel?</h3>
        <ul class="space-y-2 text-gray-700">
          <li class="flex items-start"><span class="text-[#F2784B] mr-2">•</span> Staff terlatih dan bersertifikat</li>
          <li class="flex items-start"><span class="text-[#F2784B] mr-2">•</span> Lokasi strategis dan mudah dijangkau</li>
          <li class="flex items-start"><span class="text-[#F2784B] mr-2">•</span> Fasilitas modern dan nyaman</li>
        </ul>
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
