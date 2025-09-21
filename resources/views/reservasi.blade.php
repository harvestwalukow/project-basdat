<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Reservasi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FEFBF7] text-[#333333]"> <!-- 🟡 background pastel -->

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
      <h1 class="text-4xl mb-4 font-bold text-gray-800">Form Reservasi</h1>
      <p class="text-gray-700">
        Isi form di bawah untuk melakukan reservasi hotel hewan
      </p>
    </div>

    <form action="#" method="POST" class="space-y-8">

      <!-- Informasi Pemilik -->
      <div class="bg-white shadow-md rounded-lg border border-yellow-200">
        <div class="border-b bg-yellow-50 px-4 py-3 rounded-t-lg">
          <h2 class="font-semibold text-gray-800">Informasi Pemilik</h2>
          <p class="text-sm text-gray-500">Data diri pemilik hewan peliharaan</p>
        </div>
        <div class="p-4 grid md:grid-cols-2 gap-4">
          <div>
            <label for="ownerName" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
            <input type="text" id="ownerName" name="ownerName" class="mt-1 block w-full border rounded p-2" required>
          </div>
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon *</label>
            <input type="text" id="phone" name="phone" class="mt-1 block w-full border rounded p-2" required>
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full border rounded p-2">
          </div>
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
            <input type="text" id="address" name="address" class="mt-1 block w-full border rounded p-2">
          </div>
        </div>
      </div>

      <!-- Informasi Hewan -->
      <div class="bg-white shadow-md rounded-lg border border-yellow-200">
        <div class="border-b bg-yellow-50 px-4 py-3 rounded-t-lg">
          <h2 class="font-semibold text-gray-800">Informasi Hewan Peliharaan</h2>
          <p class="text-sm text-gray-500">Data hewan yang akan dititipkan</p>
        </div>
        <div class="p-4 grid md:grid-cols-2 gap-4">
          <div>
            <label for="petName" class="block text-sm font-medium text-gray-700">Nama Hewan *</label>
            <input type="text" id="petName" name="petName" class="mt-1 block w-full border rounded p-2" required>
          </div>
          <div>
            <label for="petType" class="block text-sm font-medium text-gray-700">Jenis Hewan *</label>
            <select id="petType" name="petType" class="mt-1 block w-full border rounded p-2" required>
              <option value="">Pilih jenis hewan</option>
              <option value="dog">Anjing</option>
              <option value="cat">Kucing</option>
              <option value="rabbit">Kelinci</option>
              <option value="bird">Burung</option>
              <option value="other">Lainnya</option>
            </select>
          </div>
          <div>
            <label for="petBreed" class="block text-sm font-medium text-gray-700">Ras/Breed</label>
            <input type="text" id="petBreed" name="petBreed" class="mt-1 block w-full border rounded p-2">
          </div>
          <div>
            <label for="petAge" class="block text-sm font-medium text-gray-700">Umur (tahun)</label>
            <input type="number" id="petAge" name="petAge" class="mt-1 block w-full border rounded p-2">
          </div>
          <div>
            <label for="petWeight" class="block text-sm font-medium text-gray-700">Berat Badan (kg)</label>
            <input type="number" step="0.1" id="petWeight" name="petWeight" class="mt-1 block w-full border rounded p-2">
          </div>
        </div>
      </div>

      <!-- Detail Reservasi -->
      <div class="bg-white shadow-md rounded-lg border border-yellow-200">
        <div class="border-b bg-yellow-50 px-4 py-3 rounded-t-lg">
          <h2 class="font-semibold text-gray-800">Detail Reservasi</h2>
          <p class="text-sm text-gray-500">Pilih paket dan tanggal menginap</p>
        </div>
        <div class="p-4 space-y-4">
          <div>
            <label for="packageType" class="block text-sm font-medium text-gray-700">Pilih Paket *</label>
            <select id="packageType" name="packageType" class="mt-1 block w-full border rounded p-2" required>
              <option value="">Pilih paket layanan</option>
              <option value="basic">Paket Basic - Rp 150.000</option>
              <option value="premium">Paket Premium - Rp 250.000</option>
            </select>
          </div>

          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label for="checkInDate" class="block text-sm font-medium text-gray-700">Tanggal Check-in *</label>
              <input type="date" id="checkInDate" name="checkInDate" class="mt-1 block w-full border rounded p-2" required>
            </div>
            <div>
              <label for="checkOutDate" class="block text-sm font-medium text-gray-700">Tanggal Check-out *</label>
              <input type="date" id="checkOutDate" name="checkOutDate" class="mt-1 block w-full border rounded p-2" required>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Navbar -->
      <div class="flex justify-end space-x-4 mt-6 border-t pt-4">
        <button 
          type="button" 
          class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100"
          onclick="window.location.href='{{ url('/') }}'">
          Kembali
        </button>
        <button 
          type="submit" 
          class="px-4 py-2 rounded bg-[#F2784B] hover:bg-[#e0673d] text-white">
          Lanjut ke Pembayaran
        </button>
      </div>

    </form>
  </div>

</body>
</html>
