<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PawsHotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Baloo+2:wght@600;700&display=swap" rel="stylesheet">
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

  <!-- HERO -->
  <section class="relative px-8 py-20">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-10">
      <div class="space-y-6">
        <h1 class="text-5xl font-bold leading-tight text-gray-900" style="font-family: 'Baloo 2', cursive;">
          Rumah Hangat <br> untuk Sahabat Berbulu 🐾
        </h1>
        <p class="text-lg text-gray-700">
          Tempat penitipan hewan dengan kamar nyaman, area bermain seru, dan update harian yang bikin tenang.
        </p>
        <div class="flex gap-4">
          <a href="{{ url('/reservasi') }}" class="px-6 py-3 rounded-full bg-orange-400 text-white font-semibold shadow hover:bg-orange-500">
            Daftar Sekarang
          </a>
          <a href="#fasilitas" class="px-6 py-3 rounded-full border border-orange-400 text-orange-500 font-semibold hover:bg-orange-50">
            Lihat Fasilitas →
          </a>
        </div>
      </div>
      <div class="flex justify-center">
        <img src="/img/anjing.png" alt="Anjing dan Kucing"
             class="max-h-[600px] md:max-h-[750px] object-contain drop-shadow-lg animate-bounce-slow">
      </div>
    </div>
  </section>

<!-- FASILITAS -->
<section id="fasilitas" class="relative py-20 bg-[#FFF7F2]">
  <div class="absolute inset-0 opacity-10 bg-[url('/img/paw-pattern.png')] bg-repeat"></div>
  <div class="relative max-w-7xl mx-auto px-6 text-center">
    <h2 class="text-4xl font-bold mb-3">Fasilitas Kami</h2>
    <p class="text-gray-600 max-w-3xl mx-auto mb-12">
      Semua yang dibutuhkan agar hewan peliharaan merasa aman, nyaman, dan bahagia selama menginap.
    </p>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Card 1 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/icon-ac.png" class="w-14 mx-auto mb-3" alt="AC">
        <h3 class="text-xl font-semibold mb-1">Kamar Ber-AC</h3>
        <p class="text-gray-600">Suhu ruangan stabil untuk istirahat yang nyaman.</p>
      </div>

      <!-- Card 2 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/icon-cctv.png" class="w-14 mx-auto mb-3" alt="CCTV">
        <h3 class="text-xl font-semibold mb-1">CCTV 24/7</h3>
        <p class="text-gray-600">Pengawasan nonstop; bisa minta video update.</p>
      </div>

      <!-- Card 3 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/icon-play.png" class="w-14 mx-auto mb-3" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Area Bermain</h3>
        <p class="text-gray-600">Indoor/outdoor dengan permainan & obstacle.</p>
      </div>

      <!-- Card 4 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/icon-play.png" class="w-14 mx-auto mb-3" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Dapur Higienis</h3>
        <p class="text-gray-600">Menu bergizi & bisa disesuaikan diet.</p>
      </div>

      <!-- Card 5 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/icon-play.png" class="w-14 mx-auto mb-3" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Sterilisasi Rutin</h3>
        <p class="text-gray-600">Kandang & mainan disanitasi berkala.</p>
      </div>

      <!-- Card 6 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/icon-play.png" class="w-14 mx-auto mb-3" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Update Harian</h3>
        <p class="text-gray-600">Foto/Video via WhatsApp; opsi VC sesuai paket.</p>
      </div>
    </div>
  </div>
</section>


  <!-- TESTIMONI -->
  <section id="testimoni" class="py-16 bg-[#FEFBF7]/60 relative">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Apa Kata Pelanggan Kami</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">
      <div class="testimonial bg-white p-6 rounded-2xl shadow-lg opacity-0 translate-y-10 scale-95">
        <img src="/img/user1.jpg" class="w-16 h-16 rounded-full mx-auto mb-4 object-cover" alt="Sarah">
        <p class="text-yellow-400 text-xl mb-3">★★★★★</p>
        <p class="text-gray-700 mb-4">"Kucing saya Mimi terlihat bahagia dan sehat setelah menginap 1 minggu."</p>
        <p class="font-semibold text-gray-800">Sarah Wijayanto</p>
      </div>
      <div class="testimonial bg-white p-6 rounded-2xl shadow-lg opacity-0 translate-y-10 scale-95">
        <img src="/img/user2.jpg" class="w-16 h-16 rounded-full mx-auto mb-4 object-cover" alt="Rama">
        <p class="text-yellow-400 text-xl mb-3">★★★★★</p>
        <p class="text-gray-700 mb-4">"Area bermainnya luas, staf ramah, dan komunikatif."</p>
        <p class="font-semibold text-gray-800">Rama Putra</p>
      </div>
      <div class="testimonial bg-white p-6 rounded-2xl shadow-lg opacity-0 translate-y-10 scale-95">
        <img src="/img/user3.jpg" class="w-16 h-16 rounded-full mx-auto mb-4 object-cover" alt="Laras">
        <p class="text-yellow-400 text-xl mb-3">★★★★★</p>
        <p class="text-gray-700 mb-4">"Update hariannya bikin tenang. Sangat direkomendasikan!"</p>
        <p class="font-semibold text-gray-800">Laras Anindya</p>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="flex justify-center space-x-16 py-10">
    <div class="text-center">
      <p class="text-4xl font-bold counter" data-target="24">0</p>
      <p class="text-gray-600">Perawatan</p>
    </div>
    <div class="text-center">
      <p class="text-4xl font-bold counter" data-target="500">0</p>
      <p class="text-gray-600">Pet Senang</p>
    </div>
    <div class="text-center">
      <p class="text-4xl font-bold flex items-center">
        <span class="counter" data-target="5">0</span>
        <svg class="w-8 h-8 text-yellow-400 ml-1" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.368-2.448a1 1 0 00-1.175 0l-3.368 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/>
        </svg>
      </p>
      <p class="text-gray-600">Rating</p>
    </div>
  </section>

  <!-- CTA AKHIR -->
  <section class="relative py-20 bg-gradient-to-r from-orange-100 via-orange-50 to-pink-100">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Siap Reservasi untuk Sahabat Berbulu? 🐶🐱</h2>
        <p class="text-gray-700 mb-6">Pastikan hewan kesayangan Anda mendapatkan pengalaman terbaik dengan layanan premium dari PawsHotel.</p>
        <a href="{{ url('/reservasi') }}" class="px-8 py-4 bg-[#F2784B] hover:bg-[#e0673d] text-white font-semibold rounded-xl shadow-lg">
          Reservasi Sekarang →
        </a>
      </div>
      <div class="flex justify-center">
        <img src="/img/catdog-happy.png" alt="Happy Pets" class="max-h-80 drop-shadow-xl">
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

  <!-- SCRIPT ANIMASI -->
  <script>
    // Fasilitas Animasi
    const facilityCards = document.querySelectorAll(".facility-card");
    const facilityObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.remove("opacity-0", "translate-y-10");
          entry.target.classList.add("opacity-100", "translate-y-0", "transition-all", "duration-700");
        } else {
          entry.target.classList.add("opacity-0", "translate-y-10");
          entry.target.classList.remove("opacity-100", "translate-y-0");
        }
      });
    }, { threshold: 0.2 });
    facilityCards.forEach((card, index) => {
      card.style.transitionDelay = `${index * 0.2}s`;
      facilityObserver.observe(card);
    });

    // Testimoni Animasi
    const testimonials = document.querySelectorAll('.testimonial');
    const testimonialObserver = new IntersectionObserver(entries => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.classList.remove("opacity-0", "translate-y-10", "scale-95");
            entry.target.classList.add("opacity-100", "translate-y-0", "scale-100", "transition-all", "duration-700");
          }, index * 200);
        } else {
          entry.target.classList.add("opacity-0", "translate-y-10", "scale-95");
          entry.target.classList.remove("opacity-100", "translate-y-0", "scale-100");
        }
      });
    }, { threshold: 0.2 });
    testimonials.forEach(card => testimonialObserver.observe(card));

    // Counter Animasi
    const counters = document.querySelectorAll('.counter');
    function animateCounter(counter) {
      const target = +counter.getAttribute('data-target');
      let count = 0;
      const step = Math.ceil(target / 100);
      const interval = setInterval(() => {
        count += step;
        if (count >= target) {
          count = target;
          clearInterval(interval);
        }
        counter.innerText = count;
      }, 30);
    }
    const counterObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) animateCounter(entry.target);
        else entry.target.innerText = "0";
      });
    }, { threshold: 0.5 });
    counters.forEach(counter => counterObserver.observe(counter));
  </script>
</body>
</html>
