<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - PawsHotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 flex items-center justify-center p-4">

  <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-8 items-center">
    
    <!-- Gambar Kiri -->
    <div class="hidden lg:block">
      <div class="relative h-[600px] rounded-2xl overflow-hidden">
        <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&h=600&fit=crop"
             alt="Happy pets at hotel"
             class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        <div class="absolute bottom-8 left-8 text-white">
          <h2 class="mb-2 text-2xl font-bold">Selamat Datang di PawsHotel</h2>
          <p class="text-lg opacity-90">
            Tempat terbaik untuk hewan kesayangan Anda
          </p>
        </div>
      </div>
    </div>

    <!-- Form Login -->
    <div class="w-full max-w-md mx-auto bg-white shadow-lg rounded-2xl p-8">
      <div class="text-center mb-6">
        <div class="flex items-center justify-center space-x-2 mb-4">
          <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
            🐾
          </div>
          <span class="text-2xl font-bold">PawsHotel</span>
        </div>
        <h1 class="text-xl font-semibold mb-1">Masuk ke Akun Anda</h1>
        <p class="text-gray-600 text-sm">Kelola reservasi dan jadwal hewan kesayangan Anda</p>
      </div>

      <!-- Form -->
      <form action="{{ route('signin.submit') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium">Email</label>
          <div class="relative mt-1">
            <input type="email" id="email" name="email" required
              placeholder="nama@email.com"
              class="w-full border rounded-lg px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
            <span class="absolute left-3 top-2.5 text-gray-400">📧</span>
          </div>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium">Password</label>
          <div class="relative mt-1">
            <input type="password" id="password" name="password" required
              placeholder="Masukkan password"
              class="w-full border rounded-lg px-3 py-2 pl-10 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
            <span class="absolute left-3 top-2.5 text-gray-400">🔒</span>
          </div>
        </div>

        <!-- Ingat saya & Lupa password -->
        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center space-x-2">
            <input type="checkbox" class="rounded border-gray-300" />
            <span>Ingat saya</span>
          </label>
          <a href="#" class="text-blue-600 hover:underline">Lupa password?</a>
        </div>

        <!-- Tombol Login -->
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
          Masuk
        </button>

        <!-- Link Daftar -->
        <p class="text-center text-sm text-gray-600 mt-3">
          Belum punya akun?
          <a href="{{ route('signup') }}" class="text-blue-600 hover:underline">Daftar sekarang</a>
        </p>
      </form>

      <!-- Kembali ke Beranda -->
      <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">atau</span>
          </div>
        </div>

        <a href="{{ url('/') }}"
          class="w-full mt-4 block text-center border border-gray-300 rounded-lg py-2 hover:bg-gray-100">
          Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>

</body>
</html>

