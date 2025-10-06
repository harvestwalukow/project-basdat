<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'PawsHotel - User Dashboard')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Baloo+2:wght@600;700&display=swap" rel="stylesheet">
  <style>
    html { scroll-behavior: smooth; }
    body { font-family: 'Nunito', sans-serif; }
  </style>
  @stack('styles')
</head>

<body class="@yield('body-class', 'bg-cover bg-center bg-fixed text-[#333333] antialiased')" @yield('body-style')>

<!-- NAVBAR -->
  <header class="sticky top-0 z-50 bg-[#fff2de]/90 backdrop-blur border-b border-orange-100/60">
    <div class="max-w-7xl mx-auto px-6 py-4 grid grid-cols-12 items-center gap-4">
      <a href="{{ route('dashboard') }}" class="col-span-6 md:col-span-3 text-2xl font-extrabold text-[#F2784B]">
        PawsHotel
      </a>
      <nav class="hidden md:flex col-span-6 md:col-span-6 justify-center gap-8 font-medium">
        <a href="{{ route('dashboard') }}" class="hover:text-[#F2784B] {{ request()->is('dashboard') ? 'text-[#F2784B] font-semibold' : '' }}">Dashboard</a>
        <a href="{{ route('reservasi') }}" class="hover:text-[#F2784B] {{ request()->is('reservasi') ? 'text-[#F2784B] font-semibold' : '' }}">Buat Reservasi</a>
      </nav>
      
      <!-- User Profile Dropdown -->
      <div class="col-span-6 md:col-span-3 flex justify-end items-center gap-3">
        <!-- User Info & Dropdown -->
        <div class="relative group">
          <button class="flex items-center space-x-2 bg-white border border-orange-200 rounded-xl px-4 py-2.5 hover:bg-orange-50 transition">
            <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-semibold">
              {{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}
            </div>
            <div class="hidden md:block text-left">
              <p class="text-sm font-semibold text-gray-800">{{ session('user_name', 'User') }}</p>
              <p class="text-xs text-gray-500">{{ session('user_email', 'user@example.com') }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <!-- Dropdown Menu -->
          <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
            <div class="py-2">
              <div class="px-4 py-2 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-800">{{ session('user_name', 'User') }}</p>
                <p class="text-xs text-gray-500">{{ session('user_email', 'user@example.com') }}</p>
              </div>
              
              <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#F2784B]">
                <span class="inline-block w-5">📊</span> Dashboard
              </a>
              <a href="{{ route('reservasi') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#F2784B]">
                <span class="inline-block w-5">➕</span> Buat Reservasi
              </a>
              
              <div class="border-t border-gray-100 mt-1 pt-1">
                <form action="{{ route('logout') }}" method="GET" class="m-0">
                  <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <span class="inline-block w-5">🚪</span> Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
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

