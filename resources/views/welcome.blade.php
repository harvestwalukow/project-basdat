<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PawsHotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="bg-[#FEFBF7] text-[#333333]">
    <div class="container mx-auto px-6">
        <header class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold text-[#F2784B]">PawsHotel</h1>
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#" class="hover:text-[#F2784B]">Beranda</a>
                <a href="#" class="hover:text-[#F2784B]">Layanan</a>
                <a href="#" class="hover:text-[#F2784B]">Fasilitas</a>
                <a href="#" class="hover:text-[#F2784B]">Tentang Kami</a>
                <a href="#" class="hover:text-[#F2784B]">Kontak</a>
            </nav>
            <a href="#"
                class="bg-[#F2784B] text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-600">Reservasi
                Sekarang</a>
        </header>

        <main class="text-center py-20">
            <h2 class="text-5xl font-bold mb-4 leading-tight">Rumah untuk Sahabat Berbulu Anda</h2>
            <p class="text-lg max-w-2xl mx-auto mb-8">
                Berikan kenyamanan terbaik untuk hewan kesayangan Anda dengan layanan hotel premium,
                grooming profesional, dan perawatan 24/7 dari staff berpengalaman.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="#"
                    class="bg-[#F2784B] text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-orange-600">Reservasi
                    Sekarang</a>
                <a href="#"
                    class="bg-white border border-gray-300 px-8 py-3 rounded-lg font-bold text-lg hover:bg-gray-100">Lihat
                    Fasilitas</a>
            </div>
        </main>

        <section class="py-20 bg-[#FFF7F2]">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold mb-12">Paket Layanan Kami</h2>
                <div class="flex flex-col lg:flex-row justify-center items-stretch gap-8">

                    <!-- Basic Package -->
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full lg:w-1/3 flex flex-col">
                        <h3 class="text-2xl font-bold mb-4">Basic</h3>
                        <ul class="text-left space-y-2 text-gray-600 mb-8 flex-grow">
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Kamar Ber-AC</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Makan 3x sehari</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Area bermain (bisa indoor/outdoor)</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Laporan harian via WA (foto)</span></li>
                        </ul>
                    </div>

                    <!-- Premium Package -->
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full lg:w-1/3 border-2 border-[#F2784B] flex flex-col">
                        <h3 class="text-2xl font-bold mb-4 text-[#F2784B]">Premium</h3>
                        <ul class="text-left space-y-2 text-gray-600 mb-8 flex-grow">
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Kamar Ber-AC</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Makan 3x sehari</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Area bermain (bisa indoor/outdoor)</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Laporan harian via WA + VC</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Snack</span></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Treats</span></li>
                        </ul>
                    </div>

                    <!-- Add-on -->
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full lg:w-1/3 flex flex-col">
                        <h3 class="text-2xl font-bold mb-4">Add on</h3>
                        <ul class="text-left space-y-2 text-gray-600 mb-8 flex-grow">
                            <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg><span>Kolam renang</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex justify-center space-x-16 py-10">
            <div class="text-center">
                <p class="text-4xl font-bold">24/7</p>
                <p class="text-gray-600">Perawatan</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold">500+</p>
                <p class="text-gray-600">Pet Senang</p>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold flex items-center">5 <svg class="w-8 h-8 text-yellow-400 ml-1"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.368-2.448a1 1 0 00-1.175 0l-3.368 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z">
                        </path>
                    </svg></p>
                <p class="text-gray-600">Rating</p>
            </div>
        </section>

        <section class="py-10">
            <div class="w-full max-w-4xl mx-auto">
                <img src="https://images.pexels.com/photos/4587971/pexels-photo-4587971.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                    alt="Woman with a pet" class="rounded-lg shadow-lg">
            </div>
        </section>
    </div>
</body>

</html>
