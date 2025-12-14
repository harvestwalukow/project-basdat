@extends('layouts.app')

@section('title', 'Tentang Kami - PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/backround.png\');"')

@section('content')
    <!-- Hero Section with Auto Carousel -->
    <div class="relative max-w-screen-xl mx-auto overflow-hidden rounded-2xl shadow-2xl mt-10 animate-fade-in-up">
        <div id="hero-carousel" class="relative w-full h-[450px]">
            <div class="absolute inset-0 transition-opacity duration-1000 opacity-100">
                <img src="/img/rumah.png" alt="Pet Hotel 1" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 transition-opacity duration-1000 opacity-0">
                <img src="/img/hotel.jpg" alt="Pet Hotel 2" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 transition-opacity duration-1000 opacity-0">
                <img src="/img/pet hotelss.jpg" alt="Pet Hotel 3" class="w-full h-full object-cover">
            </div>
        </div>
        <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center text-white px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang <span class="text-[#F2784B]">PawsHotel</span></h1>
            <p class="text-lg px-6 md:px-16">
                Hotel hewan khusus anjing dan kucing yang kami hadirkan sebagai rumah kedua bagi anabul kesayangan Anda, dengan fokus pada kenyamanan, keamanan, dan kebersihan. PawsHotel memahami bahwa setiap anabul punya karakter dan kebutuhan yang berbeda. Oleh karena itu, perawatan di PawsHotel dilakukan dengan perhatian intens, mulai dari rutinitas makan, waktu istirahat, hingga aktivitas bermain agar anabul tetap tenang dan bahagia selama menginap. Dengan fasilitas yang nyaman dan lingkungan yang higienis, PawsHotel ingin Anda bisa bepergian tanpa khawatir, karena anabul dirawat sepenuh hati oleh tim yang penyayang dan berpengalaman.
            </p>
        </div>
    </div>

    <!-- Misi & Visi -->
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 my-20 px-6">

        <!-- MISI -->
        <div class="group relative rounded-2xl p-[1px] bg-gradient-to-br from-[#F2784B]/70 via-[#ffd7a8] to-[#F2784B]/30
                    shadow-lg hover:shadow-2xl transition-all duration-500 animate-fade-in-left">
            <div class="relative bg-white/90 backdrop-blur rounded-2xl p-8 overflow-hidden
                        hover:-translate-y-1 transition-all duration-500">
                <!-- paw watermark -->
                <div class="absolute -right-8 -top-8 text-[#F2784B]/10 group-hover:text-[#F2784B]/15 transition duration-500">
                    <svg width="140" height="140" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 13.5c-2.6 0-4.7 1.8-4.7 4 0 1.2.7 2.1 1.8 2.6 1.1.5 2.7.8 2.9.8s1.8-.3 2.9-.8c1.1-.5 1.8-1.4 1.8-2.6 0-2.2-2.1-4-4.7-4zM8.2 8.8c-.8 0-1.6.9-1.6 2 0 1 .6 1.8 1.4 1.8.8 0 1.6-.9 1.6-2 0-1-.6-1.8-1.4-1.8zM15.8 8.8c-.8 0-1.4.8-1.4 1.8 0 1.1.8 2 1.6 2 .8 0 1.4-.8 1.4-1.8 0-1.1-.8-2-1.6-2zM6.4 6.3c-.9 0-1.7 1-1.7 2.2 0 1.1.7 2 1.6 2 .9 0 1.7-1 1.7-2.2 0-1.1-.7-2-1.6-2zM17.6 6.3c-.9 0-1.6.9-1.6 2 0 1.2.8 2.2 1.7 2.2.9 0 1.6-.9 1.6-2 0-1.2-.8-2.2-1.7-2.2z"/>
                    </svg>
                </div>

                <!-- top accent -->
                <div class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-[#F2784B] via-[#f6b26b] to-[#ffd7a8]"></div>

                <h2 class="text-2xl font-extrabold mb-4 text-[#F2784B] tracking-wide">
                    Misi:
                </h2>
                <p class="text-gray-600 leading-relaxed text-[15px] md:text-base">
                    Memberikan layanan perawatan hewan terbaik dengan fasilitas modern, tim profesional 
                    berpengalaman, dan kasih sayang seperti di rumah sendiri.
                </p>

                <!-- hover shine -->
                <div class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700">
                    <div class="absolute -left-1/2 top-0 h-full w-1/2 bg-gradient-to-r from-transparent via-white/30 to-transparent
                                rotate-12 animate-shine"></div>
                </div>
            </div>
        </div>

        <!-- VISI -->
        <div class="group relative rounded-2xl p-[1px] bg-gradient-to-br from-[#ffd7a8] via-[#F2784B]/60 to-[#ffd7a8]
                    shadow-lg hover:shadow-2xl transition-all duration-500 animate-fade-in-right">
            <div class="relative bg-white/90 backdrop-blur rounded-2xl p-8 overflow-hidden
                        hover:-translate-y-1 transition-all duration-500">
                <!-- paw watermark -->
                <div class="absolute -left-10 -bottom-10 text-[#F2784B]/10 group-hover:text-[#F2784B]/15 transition duration-500">
                    <svg width="160" height="160" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 13.5c-2.6 0-4.7 1.8-4.7 4 0 1.2.7 2.1 1.8 2.6 1.1.5 2.7.8 2.9.8s1.8-.3 2.9-.8c1.1-.5 1.8-1.4 1.8-2.6 0-2.2-2.1-4-4.7-4zM8.2 8.8c-.8 0-1.6.9-1.6 2 0 1 .6 1.8 1.4 1.8.8 0 1.6-.9 1.6-2 0-1-.6-1.8-1.4-1.8zM15.8 8.8c-.8 0-1.4.8-1.4 1.8 0 1.1.8 2 1.6 2 .8 0 1.4-.8 1.4-1.8 0-1.1-.8-2-1.6-2zM6.4 6.3c-.9 0-1.7 1-1.7 2.2 0 1.1.7 2 1.6 2 .9 0 1.7-1 1.7-2.2 0-1.1-.7-2-1.6-2zM17.6 6.3c-.9 0-1.6.9-1.6 2 0 1.2.8 2.2 1.7 2.2.9 0 1.6-.9 1.6-2 0-1.2-.8-2.2-1.7-2.2z"/>
                    </svg>
                </div>

                <!-- top accent -->
                <div class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-[#ffd7a8] via-[#f6b26b] to-[#F2784B]"></div>

                <h2 class="text-2xl font-extrabold mb-4 text-[#F2784B] tracking-wide">
                    Visi:
                </h2>
                <p class="text-gray-600 leading-relaxed text-[15px] md:text-base">
                    Menjadi hotel hewan terdepan di Indonesia yang diakui karena standar perawatan tinggi 
                    dan dedikasi terhadap kesejahteraan hewan peliharaan.
                </p>

                <!-- hover shine -->
                <div class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700">
                    <div class="absolute -left-1/2 top-0 h-full w-1/2 bg-gradient-to-r from-transparent via-white/30 to-transparent
                                rotate-12 animate-shine"></div>
                </div>
            </div>
        </div>

    </div>

    <style>
    @keyframes shine {
        0% { transform: translateX(-60%) rotate(12deg); }
        100% { transform: translateX(220%) rotate(12deg); }
    }
    .animate-shine { animation: shine 1.4s ease-in-out; }

    /* bonus: respect user yang tidak suka animasi */
    @media (prefers-reduced-motion: reduce) {
        .animate-shine { animation: none !important; }
        .animate-fade-in-left, .animate-fade-in-right { animation: none !important; }
    }
    </style>



    <!-- Cerita Kami -->
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-10 mb-20 animate-fade-in-up">
        <h2 class="text-3xl font-bold mb-6 text-center text-[#F2784B]">Cerita Kami</h2>
        <p class="text-gray-600 mb-4 leading-relaxed">
            PawsHotel bermula dari kecintaan terhadap hewan peliharaan. Didirikan oleh 
            Kelompok C Basis Data SD-A2 pada tahun 2025, di Universitas Airlangga, Surabaya.
        </p>
        <p class="text-gray-600 mb-4 leading-relaxed">
            Melihat kebutuhan pemilik hewan yang sering bepergian, kami mengembangkan hotel hewan 
            dengan standar setara hotel bintang lima.
        </p>
        <p class="text-gray-600 leading-relaxed">
            Hingga kini, PawsHotel telah melayani lebih dari {{ number_format($stats['total_hewan'] ?? 0) }} hewan peliharaan dan terus berinovasi.
        </p>
    </div>

    <!-- Tim Profesional (Auto Slider) -->
    <div class="max-w-6xl mx-auto px-6 mb-20 overflow-hidden">
    <h2 class="text-3xl font-bold text-center mb-12 text-[#F2784B] animate-fade-in-up">
        Tim Profesional Kami
    </h2>

    <div class="relative w-full">
        <div id="team-slider" class="flex transition-transform duration-700 ease-in-out">
        @foreach([
            ['/img/harvest.jpg', 'Harvest Ecclesiano C. W', '164231104', 'Back End'],
            ['/img/mayla.jpg', 'Mayla Faiza Rahma', '164231090', 'Back End'],
            ['/img/ibrahim.jpg', 'Ibrahim Ihram Hakim', '164231094', 'Back End'],
            ['/img/salwa.jpg', 'Salwa Dewi Aqiilah', '164231101', 'Front End'],
            ['/img/fatma.jpg', 'Fatma Hidayatul Khusna', '164231002', 'Front End'],
            ['/img/hanny.jpg', 'Hanny Marcelly', '164231111', 'Front End']
        ] as $member)
            <div class="min-w-[33.3333%] px-4">
            <div class="bg-[#fff1dc] rounded-xl shadow-lg p-8 text-center hover:-translate-y-2 hover:shadow-2xl transition-all duration-500">
                <!-- Foto Tim -->
                <img src="{{ $member[0] }}" alt="{{ $member[1] }}"
                    class="w-24 h-24 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-md">
                
                <!-- Info Tim -->
                <h3 class="font-bold text-gray-800">{{ $member[1] }}</h3>
                <p class="text-gray-700">{{ $member[2] }}</p>
                <p class="text-sm text-[#F2784B] font-medium">{{ $member[3] }}</p>
            </div>
            </div>
        @endforeach
        </div>
    </div>
    </div>


    <!-- Statistik -->
    <div class="bg-[#F2784B] text-white rounded-2xl p-12 max-w-6xl mx-auto mb-20 animate-fade-in-up">
        <h2 class="text-3xl mb-8 text-center font-bold">Pencapaian Kami</h2>
        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-5xl font-bold mb-2 counter" data-target="{{ $stats['total_hewan'] ?? 0 }}">0</div>
                <p>Hewan Terdaftar</p>
            </div>
            <div>
                <div class="text-5xl font-bold mb-2 counter" data-target="{{ $stats['total_penitipan'] ?? 0 }}">0</div>
                <p>Total Reservasi</p>
            </div>
            <div>
                <div class="text-5xl font-bold mb-2 counter" data-target="{{ $stats['total_customer'] ?? 0 }}">0</div>
                <p>Pelanggan Setia</p>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in-left { 0% { opacity: 0; transform: translateX(-20px); } 100% { opacity: 1; transform: translateX(0); } }
        @keyframes fade-in-right { 0% { opacity: 0; transform: translateX(20px); } 100% { opacity: 1; transform: translateX(0); } }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out both; }
        .animate-fade-in-left { animation: fade-in-left 0.8s ease-out both; }
        .animate-fade-in-right { animation: fade-in-right 0.8s ease-out both; }
    </style>

    <!-- Scripts -->
    <script>
        // Hero carousel
        const slides = document.querySelectorAll('#hero-carousel div');
        let heroIndex = 0;
        setInterval(() => {
            slides[heroIndex].classList.remove('opacity-100');
            slides[heroIndex].classList.add('opacity-0');
            heroIndex = (heroIndex + 1) % slides.length;
            slides[heroIndex].classList.remove('opacity-0');
            slides[heroIndex].classList.add('opacity-100');
        }, 4000);

        // Counter
        const counters = document.querySelectorAll('.counter');
        const speed = 100;
        const animateCounters = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 40);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });
        };
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) animateCounters();
            });
        });
        observer.observe(document.querySelector('.counter'));

        // Team slider
        const slider = document.getElementById('team-slider');
        const totalCards = slider.children.length;
        let currentSlide = 0;

        setInterval(() => {
            currentSlide++;
            if (currentSlide >= Math.ceil(totalCards / 3)) {
                currentSlide = 0;
            }
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        }, 3000);
    </script>
@endsection
