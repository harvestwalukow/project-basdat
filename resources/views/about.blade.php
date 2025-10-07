@extends('layouts.app')

@section('title', 'Tentang Kami - PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/backround.png\');"')

@section('content')
    <!-- Hero Section with Auto Carousel -->
    <div class="relative max-w-6xl mx-auto overflow-hidden rounded-2xl shadow-2xl mt-10 animate-fade-in-up">
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
            <p class="max-w-2xl text-lg">
                Hotel hewan terpercaya di Indonesia yang memberikan perawatan berkualitas tinggi untuk anjing dan kucing kesayangan Anda.
            </p>
        </div>
    </div>

    <!-- Misi & Visi -->
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 my-20 px-6">
        <div class="bg-white rounded-xl shadow-lg p-8 hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 animate-fade-in-left">
            <h2 class="text-2xl font-bold mb-4 text-[#F2784B]">Misi:</h2>
            <p class="text-gray-600 leading-relaxed">
                Memberikan layanan perawatan hewan terbaik dengan fasilitas modern, tim profesional 
                berpengalaman, dan kasih sayang seperti di rumah sendiri.
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8 hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 animate-fade-in-right">
            <h2 class="text-2xl font-bold mb-4 text-[#F2784B]">Visi:</h2>
            <p class="text-gray-600 leading-relaxed">
                Menjadi hotel hewan terdepan di Indonesia yang diakui karena standar perawatan tinggi 
                dan dedikasi terhadap kesejahteraan hewan peliharaan.
            </p>
        </div>
    </div>

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
            Hingga kini, PawsHotel telah melayani lebih dari 5.000 hewan peliharaan dan terus berinovasi.
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
        <div class="grid md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-5xl font-bold mb-2 counter" data-target="1000">0</div>
                <p>Hewan Dilayani</p>
            </div>
            <div>
                <div class="text-5xl font-bold mb-2 counter" data-target="5">0</div>
                <p>Bulan Pengalaman</p>
            </div>
            <div>
                <div class="text-5xl font-bold mb-2 counter" data-target="24">0</div>
                <p>Layanan Darurat</p>
            </div>
            <div>
                <div class="text-5xl font-bold mb-2 counter" data-target="98">0</div>
                <p>Kepuasan Pelanggan</p>
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
