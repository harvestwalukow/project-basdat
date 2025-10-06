@extends('layouts.app')

@section('title', 'Sign Up - PawsHotel')

@section('body-class', 'min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50')

@section('content')
  <div class="flex items-center justify-center p-4 py-16">
    <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-8 items-center">

    <!-- Gambar Kiri -->
    <div class="hidden lg:block">
      <div class="relative h-[600px] rounded-2xl overflow-hidden">
        <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&h=600&fit=crop"
             alt="Happy pets with owner"
             class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        <div class="absolute bottom-8 left-8 text-white">
          <h2 class="mb-2 text-2xl font-bold">Bergabung dengan PawsHotel</h2>
          <p class="text-lg opacity-90">
            Daftarkan akun untuk mulai memesan penitipan hewan kesayangan Anda.
          </p>
        </div>
      </div>
    </div>

    <!-- Form Signup -->
    <div class="w-full max-w-md mx-auto bg-white shadow-lg rounded-2xl p-8">
      <div class="text-center mb-6">
        <div class="flex items-center justify-center space-x-2 mb-4">
          <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
            🐶
          </div>
          <span class="text-2xl font-bold">PawsHotel</span>
        </div>
        <h1 class="text-xl font-semibold mb-1">Buat Akun Baru</h1>
        <p class="text-gray-600 text-sm">Daftar dan nikmati kemudahan reservasi penitipan hewan</p>
      </div>

      @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
          <ul class="mb-0 list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('signup.submit') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
          <label for="nama_lengkap" class="block text-sm font-medium">Nama Lengkap</label>
          <input type="text" id="nama_lengkap" name="nama_lengkap" required value="{{ old('nama_lengkap') }}"
            placeholder="Nama lengkap Anda"
            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium">Email</label>
          <input type="email" id="email" name="email" required value="{{ old('email') }}"
            placeholder="nama@email.com"
            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
        </div>

        <!-- No Telepon -->
        <div>
          <label for="no_telepon" class="block text-sm font-medium">No. Telepon</label>
          <input type="tel" id="no_telepon" name="no_telepon" required value="{{ old('no_telepon') }}"
            placeholder="08xxxxxxxxxx"
            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
        </div>

        <!-- Alamat -->
        <div>
          <label for="alamat" class="block text-sm font-medium">Alamat</label>
          <textarea id="alamat" name="alamat" required rows="2"
            placeholder="Alamat lengkap Anda"
            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('alamat') }}</textarea>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium">Kata Sandi</label>
          <div class="relative mt-1">
            <input type="password" id="password" name="password" required minlength="6"
              placeholder="Minimal 6 karakter"
              class="w-full border rounded-lg px-3 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
            <button type="button" onclick="togglePassword('password', this)"
              class="absolute right-3 top-2.5 text-gray-500 focus:outline-none">👁️</button>
          </div>
        </div>

        <!-- Konfirmasi Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Kata Sandi</label>
          <div class="relative mt-1">
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
              placeholder="Ulangi kata sandi"
              class="w-full border rounded-lg px-3 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
            <button type="button" onclick="togglePassword('password_confirmation', this)"
              class="absolute right-3 top-2.5 text-gray-500 focus:outline-none">👁️</button>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-[#F2784B] hover:bg-[#e0673d] text-white py-2 rounded-lg font-semibold">
          Daftar Sekarang
        </button>

        <p class="text-center text-sm text-gray-600 mt-3">
          Sudah punya akun?
          <a href="{{ route('signin') }}" class="text-blue-600 hover:underline">Masuk di sini</a>
        </p>
      </form>
    </div>
  </div>

@push('scripts')
  <script>
    function togglePassword(id, btn) {
      const input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        btn.textContent = "🙈"; // tampilkan
      } else {
        input.type = "password";
        btn.textContent = "👁️"; // sembunyikan lagi
      }
    }
  </script>
@endpush
  </div>
@endsection
