<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Reservasi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cover bg-center bg-fixed text-[#333333] antialiased"
      style="background-image: url('/img/backround.png');">

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
      <h1 class="text-4xl mb-4 font-bold text-gray-800">Form Reservasi</h1>
      <p class="text-gray-700">Isi form di bawah untuk melakukan reservasi hotel hewan</p>
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

          <!-- Layanan Tambahan -->
          <div>
            <label class="block font-medium mb-2">Layanan Tambahan (Opsional)</label>
            <div class="flex flex-col space-y-2">
              <div class="flex items-center justify-between space-x-2">
                <span>Grooming Premium (+Rp 150.000)</span>
                <div class="flex items-center space-x-2">
                  <button type="button" class="decrement bg-gray-200 px-2 rounded">-</button>
                  <input type="number" value="0" min="0" class="jumlah w-12 text-center border rounded" data-harga="150000">
                  <button type="button" class="increment bg-gray-200 px-2 rounded">+</button>
                </div>
              </div>

              <div class="flex items-center justify-between space-x-2">
                <span>Pick-up & Delivery (+Rp 100.000)</span>
                <div class="flex items-center space-x-2">
                  <button type="button" class="decrement bg-gray-200 px-2 rounded">-</button>
                  <input type="number" value="0" min="0" class="jumlah w-12 text-center border rounded" data-harga="100000">
                  <button type="button" class="increment bg-gray-200 px-2 rounded">+</button>
                </div>
              </div>

              <div class="flex items-center justify-between space-x-2">
                <span>Kolam Renang (+Rp 100.000)</span>
                <div class="flex items-center space-x-2">
                  <button type="button" class="decrement bg-gray-200 px-2 rounded">-</button>
                  <input type="number" value="0" min="0" class="jumlah w-12 text-center border rounded" data-harga="100000">
                  <button type="button" class="increment bg-gray-200 px-2 rounded">+</button>
                </div>
              </div>

              <div class="flex items-center justify-between space-x-2">
                <span>Boarding (+Rp 200.000)</span>
                <div class="flex items-center space-x-2">
                  <button type="button" class="decrement bg-gray-200 px-2 rounded">-</button>
                  <input type="number" value="0" min="0" class="jumlah w-12 text-center border rounded" data-harga="200000">
                  <button type="button" class="increment bg-gray-200 px-2 rounded">+</button>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="specialRequests">Permintaan Khusus</label>
            <textarea id="specialRequests" name="specialRequests"
              placeholder="Catatan khusus untuk perawatan hewan (alergi, obat, dll)"
              rows="3" class="w-full p-2 rounded border border-gray-300 bg-gray-50"></textarea>
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

      <div id="ringkasanBiaya" class="bg-white shadow-md rounded-lg border border-yellow-200 p-4"></div>

      <div class="flex justify-end space-x-4 mt-6 border-t pt-4">
        <button type="button" class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100" onclick="konfirmasiKembali()">Kembali</button>
        <button type="submit" class="px-4 py-2 rounded bg-[#F2784B] hover:bg-[#e0673d] text-white">Lanjut ke Pembayaran</button>
      </div>
    </form>
  </div>

  <script>
    function konfirmasiKembali() {
      if (confirm('Yakin mau kembali? Data reservasi yang sudah diisi bisa hilang.')) {
        window.location.href = '/';
      }
    }

    const packageSelect = document.getElementById('packageType');
    const ringkasan = document.getElementById('ringkasanBiaya');
    const checkInDateInput = document.getElementById('checkInDate');
    const checkOutDateInput = document.getElementById('checkOutDate');
    const jumlahInputs = document.querySelectorAll('.jumlah');
    const incrementBtns = document.querySelectorAll('.increment');
    const decrementBtns = document.querySelectorAll('.decrement');

    function hitungSelisihHari(checkIn, checkOut) {
      const tglMasuk = new Date(checkIn);
      const tglKeluar = new Date(checkOut);
      const selisih = (tglKeluar - tglMasuk) / (1000 * 60 * 60 * 24);
      return selisih >= 1 ? selisih : 1;
    }

    function updateRingkasan() {
      let total = 0;
      let html = `<h3 class="text-lg font-semibold mb-2">Ringkasan Biaya</h3>`;
      const checkIn = checkInDateInput.value;
      const checkOut = checkOutDateInput.value;
      const jumlahHari = (checkIn && checkOut) ? hitungSelisihHari(checkIn, checkOut) : 1;

      // Paket utama (dikalikan hari)
      if (packageSelect.value) {
        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
        const harga = parseInt(selectedOption.dataset.harga);
        const subtotal = harga * jumlahHari;
        total += subtotal;
        html += `<div class="flex justify-between mb-1">
          <span>${selectedOption.text} (x${jumlahHari} hari)</span>
          <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
        </div>`;
      }

      // Add-on (tidak dikalikan hari)
      jumlahInputs.forEach(input => {
        const jumlah = parseInt(input.value);
        const harga = parseInt(input.dataset.harga);
        if (jumlah > 0) {
          const subtotal = harga * jumlah;
          total += subtotal;
          html += `<div class="flex justify-between mb-1">
            <span>${input.closest('div.flex.justify-between').querySelector('span').textContent} (x${jumlah})</span>
            <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
          </div>`;
        }
      });

      if (total > 0) {
        html += `<hr class="my-2">
          <div class="flex justify-between text-sm text-gray-500 mb-1">
            <span>Lama Menginap</span>
            <span>${jumlahHari} hari</span>
          </div>
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>Rp ${total.toLocaleString('id-ID')}</span>
          </div>`;
      }

      ringkasan.innerHTML = html;
    }

    incrementBtns.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        jumlahInputs[i].value = parseInt(jumlahInputs[i].value) + 1;
        updateRingkasan();
      });
    });

    decrementBtns.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        const val = parseInt(jumlahInputs[i].value);
        if (val > 0) jumlahInputs[i].value = val - 1;
        updateRingkasan();
      });
    });

    jumlahInputs.forEach(input => input.addEventListener('change', updateRingkasan));
    packageSelect.addEventListener('change', updateRingkasan);
    checkInDateInput.addEventListener('change', updateRingkasan);
    checkOutDateInput.addEventListener('change', updateRingkasan);

    const today = new Date().toISOString().split('T')[0];
    checkInDateInput.setAttribute('min', today);

    checkInDateInput.addEventListener('change', function () {
      checkOutDateInput.min = this.value;
      if (checkOutDateInput.value < this.value) {
        checkOutDateInput.value = '';
      }
      updateRingkasan();
    });
  </script>
</body>
</html>
