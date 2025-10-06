@extends('layouts.app')

@section('title', 'Tentang Kami - PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/backround.png\');"')

@section('content')
    <!-- Hero Section -->
    <div class="text-center py-16 max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold mb-6">Tentang PawsHotel</h1>
        <p class="text-xl text-gray-600 mb-8">
            PawsHotel berdiri pada tahun 2025, merupakan hotel khusus anjing dan kucing terpercaya di Indonesia
            yang telah memberikan layanan perawatan berkualitas tinggi bagi ribuan hewan peliharaan dengan standar terbaik.
        </p>
        <img src="/img/pet hotelss.jpg" 
             alt="PetHotel facilities"
             class="rounded-lg shadow-xl w-full h-96 object-cover">
    </div>

    <!-- Misi & Visi -->
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 mb-20 px-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4">Misi:</h2>
            <p class="text-gray-600">
                Memberikan layanan perawatan hewan terbaik dengan fasilitas modern, tim profesional 
                berpengalaman, dan kasih sayang seperti di rumah sendiri.
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4">Visi:</h2>
            <p class="text-gray-600">
                Menjadi hotel hewan terdepan di Indonesia yang diakui karena standar perawatan tinggi 
                dan dedikasi terhadap kesejahteraan hewan peliharaan.
            </p>
        </div>
    </div>

    <!-- Cerita Kami -->
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-8 mb-20">
        <h2 class="text-3xl font-bold mb-6 text-center">Cerita Kami</h2>
        <p class="text-gray-600 mb-4">
            PawsHotel bermula dari kecintaan terhadap hewan peliharaan. Didirikan oleh 
            Kelompok C Basis Data SD-A2 pada tahun 2025, di Univeritas Airlangga, Surabaya.
        </p>
        <p class="text-gray-600 mb-4">
            Melihat kebutuhan pemilik hewan yang sering bepergian, kami mengembangkan hotel hewan 
            dengan standar setara hotel bintang lima.
        </p>
        <p class="text-gray-600">
            Hingga kini, PawsHotel telah melayani lebih dari 5.000 hewan peliharaan dan terus berinovasi.
        </p>
    </div>

    <!-- Tim -->
    <div class="max-w-6xl mx-auto px-6 mb-20">
        <h2 class="text-3xl font-bold text-center mb-12">Tim Profesional Kami</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="w-24 h-24 bg-[#FEFBF7] border rounded-full mx-auto mb-4 flex items-center justify-center">
                    👩‍⚕️
                </div>
                <h3 class="font-bold">Dr. Amanda Sari</h3>
                <p class="text-gray-600">Dokter Hewan Senior</p>
                <p class="text-sm text-[#F2784B]">8 tahun pengalaman - Spesialis anjing & kucing</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="w-24 h-24 bg-[#FEFBF7] border rounded-full mx-auto mb-4 flex items-center justify-center">
                    👨‍⚕️
                </div>
                <h3 class="font-bold">Dr. Robert Tanoto</h3>
                <p class="text-gray-600">Dokter Hewan</p>
                <p class="text-sm text-[#F2784B]">5 tahun pengalaman - Ahli grooming</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="w-24 h-24 bg-[#FEFBF7] border rounded-full mx-auto mb-4 flex items-center justify-center">
                    🐾
                </div>
                <h3 class="font-bold">Maya Indah</h3>
                <p class="text-gray-600">Pet Care Specialist</p>
                <p class="text-sm text-[#F2784B]">6 tahun pengalaman - Pengasuh hewan</p>
            </div>
        </div>
    </div>

    <!-- Fasilitas -->
    <div class="max-w-6xl mx-auto px-6 mb-20">
        <h2 class="text-3xl font-bold text-center mb-12">Fasilitas Lengkap</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow">Kandang VIP - Kamar mewah ber-AC</div>
            <div class="bg-white p-6 rounded shadow">Ruang Bermain Indoor - Aman & interaktif</div>
            <div class="bg-white p-6 rounded shadow">Salon Grooming - Peralatan modern</div>
            <div class="bg-white p-6 rounded shadow">Ruang Medis - Klinik lengkap</div>
            <div class="bg-white p-6 rounded shadow">Outdoor Playground - Taman luas</div>
            <div class="bg-white p-6 rounded shadow">Ruang Isolasi - Perawatan intensif</div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="bg-[#F2784B] text-white rounded-lg p-12 max-w-6xl mx-auto mb-20">
        <h2 class="text-3xl mb-8 text-center font-bold">Pencapaian Kami</h2>
        <div class="grid md:grid-cols-4 gap-8 text-center">
            <div><div class="text-4xl font-bold">1000+</div><p>Hewan Dilayani</p></div>
            <div><div class="text-4xl font-bold">5</div><p>Bulan Pengalaman</p></div>
            <div><div class="text-4xl font-bold">24/7</div><p>Layanan Darurat</p></div>
            <div><div class="text-4xl font-bold">98%</div><p>Kepuasan Pelanggan</p></div>
        </div>
    </div>
@endsection
