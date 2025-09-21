<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PawsHotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <style>html{scroll-behavior:smooth} body{font-family:'Nunito',sans-serif}</style>
</head>
<body class="bg-[#FEFBF7] text-[#333]">

  <!-- NAVBAR -->
  <header class="sticky top-0 z-50 bg-[#FEFBF7]/90 backdrop-blur border-b border-orange-100/60">
    <div class="max-w-7xl mx-auto px-6 py-4 grid grid-cols-12 items-center gap-4">
      <a href="{{ url('/') }}" class="col-span-6 md:col-span-3 text-2xl font-extrabold text-[#F2784B]">PawsHotel</a>
      <nav class="hidden md:flex col-span-6 md:col-span-6 justify-center gap-8 font-medium">
        <a href="{{ url('/') }}" class="hover:text-[#F2784B]">Beranda</a>
        <a href="{{ url('/layanan') }}" class="hover:text-[#F2784B]">Layanan</a> <!-- ⬅️ pindah ke page -->
        <a href="#fasilitas" class="hover:text-[#F2784B]">Fasilitas</a>
        <a href="{{ url('/about') }}" class="hover:text-[#F2784B]">Tentang Kami</a>
        <a href="{{ url('/kontak') }}" class="hover:text-[#F2784B]">Kontak</a>
      </nav>
      <div class="col-span-6 md:col-span-3 flex justify-end">
        <a href="{{ url('/reservasi') }}" class="inline-block rounded-xl bg-[#F2784B] px-5 py-2.5 text-white font-semibold hover:bg-[#e0673d]">
          Reservasi Sekarang
        </a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="pt-14 pb-20">
    <div class="max-w-5xl mx-auto px-6 text-center">
      <h1 class="text-4xl md:text-6xl font-extrabold mb-5">Rumah untuk Sahabat Berbulu Anda</h1>
      <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-10">
        Berikan kenyamanan terbaik untuk hewan kesayangan Anda dengan penitipan hangat,
        area bermain seru, dan update harian yang bikin tenang.
      </p>
      <div class="flex justify-center gap-4">
        <a href="{{ url('/reservasi') }}" class="rounded-xl bg-[#F2784B] px-8 py-3 text-white font-bold hover:bg-[#e0673d]">Daftar Sekarang</a>
        <a href="#fasilitas" class="rounded-xl bg-white border border-gray-300 px-8 py-3 font-bold hover:bg-gray-100">Lihat Fasilitas</a>
      </div>
    </div>
  </section>

  <!-- (tidak ada section layanan di landing) -->

  <!-- TESTIMONI, STATS, IMAGE … (biarkan punyamu) -->

  <!-- FASILITAS -->
  <section id="fasilitas" class="scroll-mt-24 py-20 bg-[#FFF7F2]">
    <!-- … punyamu yang fasilitas … -->
  </section>
</body>
</html>
