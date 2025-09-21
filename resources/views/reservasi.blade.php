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

    <form action="{{ route('reservasi.submit') }}" method="POST" class="space-y-8">
      @csrf

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
            </select>
          </div>
          <div>
            <label for="petBreed" class="block text-sm font-medium text-gray-700">Ras/Breed</label>
            <input type="text" id="petBreed" name="petBreed" class="mt-1 block w-full border rounded p-2">
          </div>
          <div>
            <label for="petAge" class="block text-sm font-medium text-gray-700">Umur (bulan)</label>
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
              <option value="basic" data-harga="150000">Paket Basic - Rp 150.000</option>
              <option value="premium" data-harga="250000">Paket Premium - Rp 250.000</option>
            </select>
          </div>

          <!-- Layanan Tambahan (Vertikal) -->
          <div>
            <label class="block font-medium mb-2">Layanan Tambahan (Opsional)</label>
            <div class="flex flex-col space-y-2">
              <label class="flex items-center space-x-2">
                <input type="checkbox" name="additionalServices" value="Grooming Premium" data-harga="150000" class="w-5 h-5">
                <span>Grooming Premium (+Rp 150.000)</span>
              </label>
              <label class="flex items-center space-x-2">
                <input type="checkbox" name="additionalServices" value="Pick-up & Delivery" data-harga="100000" class="w-5 h-5">
                <span>Pick-up & Delivery (+Rp 100.000)</span>
              </label>
              <label class="flex items-center space-x-2">
                <input type="checkbox" name="additionalServices" value="Kolam Renang" data-harga="100000" class="w-5 h-5">
                <span>Kolam Renang (+Rp 100.000)</span>
              </label>
              <label class="flex items-center space-x-2">
                <input type="checkbox" name="additionalServices" value="Boarding" data-harga="200000" class="w-5 h-5">
                <span>Boarding (+Rp 200.000)</span>
              </label>
            </div>
          </div>

          <!-- Permintaan Khusus -->
          <div class="form-group">
            <label for="specialRequests">Permintaan Khusus</label>
            <textarea 
              id="specialRequests"
              name="specialRequests"
              placeholder="Catatan khusus untuk perawatan hewan (alergi, obat, dll)"
              rows="3"
              class="w-full p-2 rounded border border-gray-300 bg-gray-50"
            ></textarea>
          </div>

          <!-- Tanggal -->
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

      <!-- Ringkasan Biaya -->
      <div id="ringkasanBiaya" class="bg-white shadow-md rounded-lg border border-yellow-200 p-4"></div>

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

  <!-- Script untuk Ringkasan Biaya -->
  <script>
    const packageSelect = document.getElementById('packageType');
    const serviceCheckboxes = document.querySelectorAll('input[name="additionalServices"]');
    const ringkasan = document.getElementById('ringkasanBiaya');

    function updateRingkasan() {
      let total = 0;
      let html = `<h3 class="text-lg font-semibold mb-2">Ringkasan Biaya</h3>`;

      // Paket utama
      if (packageSelect.value) {
        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
        const harga = parseInt(selectedOption.dataset.harga);
        total += harga;
        html += `
          <div class="flex justify-between mb-1">
            <span>${selectedOption.text}</span>
            <span>Rp ${harga.toLocaleString('id-ID')}</span>
          </div>
        `;
      }

      // Layanan tambahan
      serviceCheckboxes.forEach(cb => {
        if (cb.checked) {
          const harga = parseInt(cb.dataset.harga);
          total += harga;
          html += `
            <div class="flex justify-between mb-1">
              <span>${cb.value}</span>
              <span>Rp ${harga.toLocaleString('id-ID')}</span>
            </div>
          `;
        }
      });

      // Divider + Total
      if (total > 0) {
        html += `<hr class="my-2">
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>Rp ${total.toLocaleString('id-ID')}</span>
          </div>`;
      }

      ringkasan.innerHTML = html;
    }

    packageSelect.addEventListener('change', updateRingkasan);
    serviceCheckboxes.forEach(cb => cb.addEventListener('change', updateRingkasan));
  </script>
</body>
</html>
