@extends('layouts.app')

@section('title', 'PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/bg2.png\');"')

@section('content')
  <!-- HERO -->
  <section class="relative px-8 py-20">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-10">
      <div class="space-y-6">
        <h1 class="text-5xl font-bold leading-tight text-gray-900" style="font-family: 'Baloo 2', cursive;">
          Rumah Hangat <br> untuk Sahabat Berbulu 🐾
        </h1>
        <p class="text-lg text-gray-700">
          Tempat penitipan <strong><span class="text-[#F2784B]">khusus anjing dan kucing</strong> dengan kamar nyaman, area bermain seru, dan update harian yang bikin tenang.
        </p>
        <div class="flex gap-4">
          <a href="{{ route('reservasi') }}" class="px-6 py-3 rounded-full bg-orange-400 text-white font-semibold shadow hover:bg-orange-500">
            Reservasi Sekarang
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

<!-- SYARAT & KETENTUAN -->
  <section id="syarat" class="relative py-8 bg-[#FEFBF7] border-t border-orange-100 overflow-hidden mt-4">
    <div class="max-w-6xl mx-auto px-6 text-center">
      <h2 class="text-4xl font-bold text-gray-800 mb-4">
        <span class="text-[#F2784B]">Syarat & Ketentuan Penitipan</span>
      </h2>
      <p class="text-gray-600 mb-4 max-w-3xl mx-auto">
        Sebelum menitipkan anjing atau kucing kesayanganmu, pastikan kamu sudah membaca dan menyetujui ketentuan berikut ya 🐾
      </p>


      <!-- Scroll Container -->
      <div id="syarat-slider"
        class="flex overflow-x-auto snap-x snap-mandatory gap-5 pb-6 scrollbar-hide scroll-smooth">

        @foreach([
          ['🐶', 'Kesehatan Hewan', 'Hanya menerima <strong>anjing dan kucing</strong> dengan kondisi sehat (tidak sakit menular atau luka terbuka).', '/img/health.jpg'],
          ['💉', 'Vaksin Lengkap', 'Hewan wajib sudah <strong>vaksin lengkap</strong> dengan bukti kartu vaksin saat check-in.', '/img/vaccine.jpg'],
          ['🧴', 'Perlengkapan Pribadi', 'Disarankan membawa <strong>makanan, obat rutin,</strong> atau perlengkapan pribadi agar hewan tetap nyaman.', '/img/personal.jpg'],
          ['📸', 'Update Harian', 'Pihak PawsHotel akan mengirimkan <strong>update foto/video harian</strong> sesuai paket yang dipilih.', '/img/update.jpg'],
          ['🕓', 'Jam Operasional', 'Waktu check-in & check-out PawsHotel maksimal pukul <strong>10.00–20.00 WIB.</strong>', '/img/time.jpg'],
          ['💰', 'Pembatalan', 'Pembatalan mendadak (<24 jam sebelum jadwal) akan dikenakan <strong>biaya 50%</strong> dari total reservasi.', '/img/money.jpg'],
          ['⚠️', 'Tanggung Jawab', 'Pihak PawsHotel tidak bertanggung jawab atas kondisi bawaan atau penyakit yang tidak diinformasikan sebelumnya.', '/img/warning.jpg']
        ] as $item)
        <div class="min-w-[90%] sm:min-w-[48%] lg:min-w-[32%] snap-center">
          <div
            class="grid grid-cols-3 gap-3 items-center h-full px-5 py-4 rounded-2xl bg-gradient-to-r from-orange-400 to-orange-500 text-white shadow-md hover:shadow-xl hover:scale-[1.015] transition-all duration-500">
            
            <!-- Left Text -->
            <div class="col-span-2 text-left">
              <h3 class="text-lg font-semibold flex items-center gap-2 mb-1 whitespace-nowrap">
                <span class="text-xl">{{ $item[0] }}</span>
                {{ $item[1] }}
              </h3>
              <p class="text-[13px] font-normal leading-relaxed opacity-90">{!! $item[2] !!}</p>
            </div>

            <!-- Right Image -->
            <div class="flex justify-end">
              <img src="{{ $item[3] }}" alt="{{ $item[1] }}"
                class="w-20 h-20 object-cover rounded-lg shadow-md border-2 border-white/30">
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
      <!-- Navigation Dots -->
      <div class="flex justify-center space-x-2 mt-3">
        <span class="dot w-2.5 h-2.5 bg-white/70 rounded-full"></span>
        <span class="dot w-2.5 h-2.5 bg-white/40 rounded-full"></span>
        <span class="dot w-2.5 h-2.5 bg-white/40 rounded-full"></span>
      </div>
    

<!-- FASILITAS -->
<section id="fasilitas" class="relative py-8 bg-[#fabb5f] text-white">
  <div class="max-w-6xl mx-auto px-6 text-center">
  <div class="relative max-w-7xl mx-auto px-6 text-center">
    <h2 class="text-4xl font-bold mb-3">Fasilitas Kami</h2>
    <p class="text-gray-600 max-w-3xl mx-auto mb-6">
      Semua yang dibutuhkan agar hewan peliharaan merasa aman, nyaman, dan bahagia selama menginap.
    </p>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Card 1 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/hotel.jpg" class="w-full h-48 overflow-hidden rounded-md mb-1" alt="AC">
        <h3 class="text-xl font-semibold mb-1">Kamar Ber-AC</h3>
        <p class="text-gray-600">Suhu ruangan stabil untuk istirahat hewan yang nyaman.</p>
      </div>

      <!-- Card 2 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/cctv.jpg" class="w-full h-48 overflow-hidden rounded-md mb-1" alt="CCTV">
        <h3 class="text-xl font-semibold mb-1">CCTV 24/7</h3>
        <p class="text-gray-600">Pengawasan nonstop, bisa minta video update hewan peliharaan.</p>
      </div>

      <!-- Card 3 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/main.jpg" class="w-full h-48 overflow-hidden rounded-md mb-1" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Area Bermain</h3>
        <p class="text-gray-600">Indoor/outdoor dengan permainan & obstacle.</p>
      </div>

      <!-- Card 4 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/dapur.jpeg" class="w-full h-48 overflow-hidden rounded-md mb-1" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Dapur Higienis</h3>
        <p class="text-gray-600">Menu bergizi & bisa disesuaikan diet hewan.</p>
      </div>

      <!-- Card 5 -->
      <div class="facility-card bg-white rounded-2xl shadow px-6 pt-6 pb-3 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/kandang.jpeg" class="w-full h-48 overflow-hidden rounded-md mb-1" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Sterilisasi Rutin</h3>
        <p class="text-gray-600">Kandang & mainan disanitasi berkala.</p>
      </div>

      <!-- Card 6 -->
      <div class="facility-card bg-white rounded-2xl shadow p-6 hover:shadow-xl transition opacity-0 translate-y-10">
        <img src="/img/vc.jpeg" class="w-full h-48 overflow-hidden rounded-md mb-1" alt="Play">
        <h3 class="text-xl font-semibold mb-1">Update Harian</h3>
        <p class="text-gray-600">Foto/Video via WhatsApp, terdapat opsi VC sesuai paket.</p>
      </div>
    </div>
  </div>
</section>


  <!-- TESTIMONI -->
  <!-- TESTIMONI + STATS -->
  <section id="testimoni" 
    class="relative py-20 bg-cover bg-center bg-no-repeat"
    style="background-image: url('/img/bg1.png');">
    
    <!-- Overlay lembut agar teks tetap jelas -->
    <div class="absolute inset-0 bg-black/10"></div>

    <div class="relative z-10">
      <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">
        Apa Kata Pelanggan Kami
      </h2>

      <!-- Testimonial Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6 mb-16">
        <!-- Card 1 -->
        <div class="testimonial bg-[#fff5e6] rounded-2xl shadow-lg p-6 hover:shadow-xl transition-transform duration-300 hover:scale-[1.02] opacity-0 translate-y-10">
          <img src="/img/user1.jpg" class="w-full h-48 overflow-hidden rounded-md mb-4 object-cover" alt="Sarah">
          <p class="text-yellow-400 text-xl mb-3">★★★★★</p>
          <p class="text-gray-800 mb-4 italic">
            "Kucing saya Mimi terlihat bahagia dan sehat setelah menginap 1 minggu."
          </p>
          <p class="font-semibold text-gray-900">Sarah Wijayanto</p>
        </div>

        <!-- Card 2 -->
        <div class="testimonial bg-[#fff5e6] rounded-2xl shadow-lg p-6 hover:shadow-xl transition-transform duration-300 hover:scale-[1.02] opacity-0 translate-y-10">
          <img src="/img/user2.jpg" class="w-full h-48 overflow-hidden rounded-md mb-4 object-cover" alt="Rama">
          <p class="text-yellow-400 text-xl mb-3">★★★★★</p>
          <p class="text-gray-800 mb-4 italic">
            "Area bermainnya luas, staf ramah, dan komunikatif."
          </p>
          <p class="font-semibold text-gray-900">Rama Putra</p>
        </div>

        <!-- Card 3 -->
        <div class="testimonial bg-[#fff5e6] rounded-2xl shadow-lg p-6 hover:shadow-xl transition-transform duration-300 hover:scale-[1.02] opacity-0 translate-y-10">
          <img src="/img/user3.jpg" class="w-full h-48 overflow-hidden rounded-md mb-4 object-cover" alt="Laras">
          <p class="text-yellow-400 text-xl mb-3">★★★★★</p>
          <p class="text-gray-800 mb-4 italic">
            "Update hariannya bikin tenang. Sangat direkomendasikan!"
          </p>
          <p class="font-semibold text-gray-900">Laras Anindya</p>
        </div>
      </div>

      <!-- Stats Counter -->
      <div class="flex flex-col md:flex-row justify-center items-center md:space-x-16 space-y-8 md:space-y-0">
        <div class="text-center">
          <p class="text-4xl font-bold text-gray-800 counter" data-target="{{ $stats['total_staff'] }}">0</p>
          <p class="text-gray-700">Staff Profesional</p>
        </div>
        <div class="text-center">
          <p class="text-4xl font-bold text-gray-800 counter" data-target="{{ $stats['total_hewan'] }}">0</p>
          <p class="text-gray-700">Pet Senang</p>
        </div>

      </div>
    </div>
  </section>


  <!-- CTA AKHIR -->
  <section class="relative py-20 bg-gradient-to-r from-orange-100 via-orange-50 to-pink-100">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Siap Reservasi untuk Sahabat Berbulu? 🐶🐱</h2>
        <p class="text-gray-700 mb-6">Pastikan hewan kesayangan Anda mendapatkan pengalaman terbaik dengan layanan premium dari PawsHotel.</p>
        <a href="{{ route('reservasi') }}" class="px-8 py-4 bg-[#F2784B] hover:bg-[#e0673d] text-white font-semibold rounded-xl shadow-lg">
          Reservasi Sekarang →
        </a>
      </div>
      <div class="flex justify-center">
        <img src="/img/rumah.png" alt="Happy Pets" class="w-full h-50 overflow-hidden rounded-md mb-3" alt="Play">
      </div>
    </div>
  </section>

@push('scripts')
  <script>
    // Script dots slider
    const slider = document.getElementById('syarat-slider');
    const dots = document.querySelectorAll('#dots .dot');
    slider.addEventListener('scroll', () => {
      const scrollLeft = slider.scrollLeft;
      const width = slider.scrollWidth - slider.clientWidth;
      const section = Math.round((scrollLeft / width) * (dots.length - 1));
      dots.forEach((dot, i) => {
        dot.classList.toggle('bg-orange-400', i === section);
        dot.classList.toggle('bg-gray-300', i !== section);
      });
    });

    // Hilangkan scrollbar
    const style = document.createElement('style');
    style.innerHTML = `
      .scrollbar-hide::-webkit-scrollbar { display: none; }
      .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    `;
    document.head.appendChild(style);
  </script>


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
@endpush
@endsection