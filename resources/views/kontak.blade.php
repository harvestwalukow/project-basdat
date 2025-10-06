@extends('layouts.app')

@section('title', 'Hubungi Kami | PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/backround.png\');"')

@section('content')
  <div class="max-w-6xl mx-auto px-6 py-16">
    <!-- Header -->
    <div class="text-center mb-16">
      <h1 class="text-4xl font-bold mb-6">Hubungi Kami</h1>
      <p class="text-xl text-gray-600 max-w-3xl mx-auto">
        Ada pertanyaan tentang layanan kami? Tim customer service siap membantu Anda 24/7
      </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-12">
      <!-- Contact Form -->
      <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-4">Kirim Pesan</h2>
        <p class="text-gray-600 mb-6">Isi form di bawah dan kami akan menghubungi Anda dalam 24 jam</p>

        <form class="space-y-4">
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap *</label>
              <input id="name" type="text" class="w-full border rounded px-3 py-2" placeholder="Masukkan nama lengkap" required>
            </div>
            <div>
              <label for="phone" class="block text-sm font-medium mb-1">Nomor Telepon</label>
              <input id="phone" type="text" class="w-full border rounded px-3 py-2" placeholder="08xxxxxxxxxx">
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium mb-1">Email *</label>
            <input id="email" type="email" class="w-full border rounded px-3 py-2" placeholder="email@example.com" required>
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium mb-1">Subjek</label>
            <input id="subject" type="text" class="w-full border rounded px-3 py-2" placeholder="Subjek pesan">
          </div>

          <div>
            <label for="message" class="block text-sm font-medium mb-1">Pesan *</label>
            <textarea id="message" rows="5" class="w-full border rounded px-3 py-2" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
          </div>

          <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded">
            Kirim Pesan
          </button>
        </form>
      </div>

      <!-- Contact Information -->
      <div class="space-y-6">

        <!-- Contact Details -->
        <div class="space-y-4">
          <div class="bg-white shadow rounded-lg p-6 flex items-start space-x-4">
            <div class="text-2xl">📍</div>
            <div>
              <h3 class="font-semibold mb-2">Alamat</h3>
              <p class="text-gray-600 text-sm">Jl. Raya Kebayoran No. 123</p>
              <p class="text-gray-600 text-sm">Jakarta Selatan 12345</p>
            </div>
          </div>

          <div class="bg-white shadow rounded-lg p-6 flex items-start space-x-4">
            <div class="text-2xl">📞</div>
            <div>
              <h3 class="font-semibold mb-2">Telepon</h3>
              <p class="text-gray-600 text-sm">+62 21 1234 5678</p>
              <p class="text-gray-600 text-sm">+62 811 2345 6789</p>
            </div>
          </div>

          <div class="bg-white shadow rounded-lg p-6 flex items-start space-x-4">
            <div class="text-2xl">📧</div>
            <div>
              <h3 class="font-semibold mb-2">Email</h3>
              <p class="text-gray-600 text-sm">info@pethotel.co.id</p>
              <p class="text-gray-600 text-sm">booking@pethotel.co.id</p>
            </div>
          </div>

          <div class="bg-white shadow rounded-lg p-6 flex items-start space-x-4">
            <div class="text-2xl">🕒</div>
            <div>
              <h3 class="font-semibold mb-2">Jam Operasional</h3>
              <p class="text-gray-600 text-sm">Senin - Jumat: 07:00 - 20:00</p>
              <p class="text-gray-600 text-sm">Sabtu - Minggu: 08:00 - 18:00</p>
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="font-semibold mb-2">Media Sosial</h3>
          <p class="text-gray-600 mb-4">Ikuti kami untuk update terbaru</p>
          <div class="space-y-3">
            <a href="https://wa.me/6281123456789" target="_blank" class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
              <div class="flex items-center space-x-3">
                <span class="text-xl">💬</span>
                <div>
                  <p class="text-sm">WhatsApp</p>
                  <p class="text-xs text-gray-600">+62 811 2345 6789</p>
                </div>
              </div>
              <span class="text-blue-600 text-sm">Follow</span>
            </a>
            <a href="#" class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
              <div class="flex items-center space-x-3">
                <span class="text-xl">📷</span>
                <div>
                  <p class="text-sm">Instagram</p>
                  <p class="text-xs text-gray-600">@pethotel_id</p>
                </div>
              </div>
              <span class="text-blue-600 text-sm">Follow</span>
            </a>
            <a href="#" class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
              <div class="flex items-center space-x-3">
                <span class="text-xl">📘</span>
                <div>
                  <p class="text-sm">Facebook</p>
                  <p class="text-xs text-gray-600">PetHotel Indonesia</p>
                </div>
              </div>
              <span class="text-blue-600 text-sm">Follow</span>
            </a>
            <a href="#" class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
              <div class="flex items-center space-x-3">
                <span class="text-xl">🎵</span>
                <div>
                  <p class="text-sm">TikTok</p>
                  <p class="text-xs text-gray-600">@pethotel.id</p>
                </div>
              </div>
              <span class="text-blue-600 text-sm">Follow</span>
            </a>
          </div>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
          <h3 class="text-red-800 font-semibold mb-2">Kontak Darurat</h3>
          <p class="text-red-600 mb-3">Untuk situasi darurat di luar jam operasional</p>
          <div class="flex items-center space-x-2 mb-2">
            <span class="text-red-600">🚨</span>
            <span>Emergency Hotline: +62 811 9999 8888</span>
          </div>
          <p class="text-sm text-red-600">Tersedia 24/7 untuk kasus medis darurat hewan peliharaan</p>
        </div>
      </div>
    </div>

    <!-- Map Section -->
    <div class="mt-16 bg-white shadow rounded-lg p-8">
      <h3 class="font-semibold mb-2">Lokasi Kami</h3>
      <p class="text-gray-600 mb-6">Kunjungi hotel hewan kami di Jakarta Selatan</p>
      <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
        <div class="text-center text-gray-500">
          <div class="text-4xl mb-2">🗺️</div>
          <p>Google Maps Integration</p>
          <p class="text-sm">Jl. Raya Kebayoran No. 123, Jakarta Selatan</p>
        </div>
      </div>
    </div>

    <!-- FAQ Section -->
    <div class="mt-16">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold mb-4">Pertanyaan Umum</h2>
        <p class="text-gray-600">Berikut beberapa pertanyaan yang sering diajukan</p>
      </div>
      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-2">Jam berapa check-in dan check-out?</h3>
          <p class="text-gray-600">Check-in mulai pukul 08:00 dan check-out hingga pukul 17:00. Untuk di luar jam tersebut, silakan hubungi kami.</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-2">Apa saja yang perlu dibawa saat check-in?</h3>
          <p class="text-gray-600">Bawa surat vaksin terbaru, makanan khusus (jika ada), dan mainan favorit hewan peliharaan Anda.</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-2">Bisakah saya mengunjungi hewan saya?</h3>
          <p class="text-gray-600">Ya, Anda bisa mengunjungi hewan peliharaan setiap hari dari pukul 10:00-16:00 dengan perjanjian.</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-2">Bagaimana jika hewan saya sakit?</h3>
          <p class="text-gray-600">Kami memiliki dokter hewan standby 24/7. Biaya konsultasi dan pengobatan akan diinformasikan terlebih dahulu.</p>
        </div>
      </div>
    </div>
  </div>
@endsection
