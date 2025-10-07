<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'PawsHotel')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Baloo+2:wght@600;700&display=swap" rel="stylesheet">
  <style>
    html { scroll-behavior: smooth; }
    body { font-family: 'Nunito', sans-serif; }
  </style>
  @stack('styles')
</head>

<body class="bg-cover bg-center bg-fixed text-[#333333] antialiased" 
      style="background-image: url('{{ asset('img/backround.png') }}'); background-size: cover; background-attachment: fixed;">


<!-- NAVBAR -->
  <header class="sticky top-0 z-50 bg-[#fff2de]/90 backdrop-blur border-b border-orange-100/60">
    <div class="max-w-7xl mx-auto px-6 py-4 grid grid-cols-12 items-center gap-4">
      <a href="{{ url('/') }}" class="col-span-6 md:col-span-3 flex items-center space-x-2">
        <img src="{{ asset('img/logo1.png') }}" alt="PawsHotel Logo" class="w-8 h-8 md:w-10 md:h-10">
        <span class="text-2xl font-extrabold text-[#F2784B]">PawsHotel</span>
      </a>

      <nav class="hidden md:flex col-span-6 md:col-span-6 justify-center gap-8 font-medium">
        <a href="{{ url('/') }}" class="hover:text-[#F2784B] transition-all {{ request()->is('/') ? 'text-[#F2784B] font-bold border-b-2 border-[#F2784B] pb-1' : '' }}">Beranda</a>
        <a href="{{ url('/layanan') }}" class="hover:text-[#F2784B] transition-all {{ request()->is('layanan') ? 'text-[#F2784B] font-bold border-b-2 border-[#F2784B] pb-1' : '' }}">Layanan</a>
        <a href="{{ url('/about') }}" class="hover:text-[#F2784B] transition-all {{ request()->is('about') ? 'text-[#F2784B] font-bold border-b-2 border-[#F2784B] pb-1' : '' }}">Tentang Kami</a>
        <a href="{{ url('/kontak') }}" class="hover:text-[#F2784B] transition-all {{ request()->is('kontak') ? 'text-[#F2784B] font-bold border-b-2 border-[#F2784B] pb-1' : '' }}">Kontak</a>
      </nav>
      <!-- Tombol Sign In & Sign Up -->
       <div class="col-span-6 md:col-span-3 flex justify-end space-x-3">
        <!-- Sign In -->
         <a href="{{ url('/signin') }}"
         class="inline-block rounded-xl border border-[#F2784B] px-5 py-2.5 text-[#F2784B] font-semibold hover:bg-[#F2784B] hover:text-white">
         Sign In
        </a>
        <!-- Sign Up (ganti dari Reservasi) -->
         <a href="{{ route('signup') }}"
          class="inline-block rounded-xl bg-[#F2784B] px-5 py-2.5 text-white font-semibold hover:bg-[#e0673d]">
          Sign Up
        </a>

      </div>
    </div>
  </header>

  <!-- CONTENT -->
  @yield('content')

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

  @stack('scripts')
</body>
</html>

