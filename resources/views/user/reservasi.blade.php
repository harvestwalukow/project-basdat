@extends('layouts.user')

@section('title', 'Form Reservasi - PawsHotel')

@section('body-style', 'style="background-image: url(\'/img/backround.png\');"')

@section('content')
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
            <input type="text" id="ownerName" name="ownerName" value="{{ $user->nama_lengkap ?? session('user_name', '') }}" class="mt-1 block w-full border rounded p-2" required>
          </div>
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon *</label>
            <input type="text" id="phone" name="phone" value="{{ $user->no_telepon ?? '' }}" class="mt-1 block w-full border rounded p-2" required>
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email ?? session('user_email', '') }}" class="mt-1 block w-full border rounded p-2">
          </div>
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
            <input type="text" id="address" name="address" value="{{ $user->alamat ?? '' }}" class="mt-1 block w-full border rounded p-2">
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

        <div class="p-6 space-y-6">
          <!-- Paket Layanan -->
          <div>
            <h3 class="text-lg font-semibold mb-2">Paket Layanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              @forelse($paketLayanans as $paket)
                <div class="bg-white rounded-lg border border-[#F2784B] shadow p-6 flex flex-col cursor-pointer hover:bg-orange-50 transition"
                     data-harga="{{ $paket->harga_per_hari }}" 
                     data-nama="{{ $paket->nama_paket }}" 
                     data-id="{{ $paket->id_paket }}"
                     onclick="pilihPaket(this)">
                  <h3 class="text-2xl font-bold mb-2 text-gray-800">{{ $paket->nama_paket }}</h3>
                  <p class="text-[#F2784B] font-bold mb-4">Rp {{ number_format($paket->harga_per_hari, 0, ',', '.') }}</p>
                  <p class="text-left text-gray-600 mb-4">{{ $paket->deskripsi }}</p>
                  @if($paket->fasilitas)
                    <ul class="text-left space-y-2 text-gray-600 mb-6">
                      @foreach(explode("\n", $paket->fasilitas) as $fasilitas)
                        @if(trim($fasilitas))
                          <li>{{ trim($fasilitas) }}</li>
                        @endif
                      @endforeach
                    </ul>
                  @endif
                </div>
              @empty
                <div class="col-span-2 text-center text-gray-500 p-6">
                  <p>Belum ada paket layanan tersedia</p>
                </div>
              @endforelse
            </div>
          </div>

          <input type="hidden" id="packageType" name="packageType">
          <input type="hidden" id="packageId" name="packageId">

          <!-- Layanan Tambahan -->
          <div>
            <label class="block font-medium mb-2">Layanan Tambahan (Opsional)</label>
            <div class="flex flex-col space-y-2">
              @forelse($layananTambahan as $layanan)
                <div class="flex items-center justify-between space-x-2">
                  <span>{{ $layanan->nama_paket }} (+Rp {{ number_format($layanan->harga_per_hari, 0, ',', '.') }})</span>
                  <div class="flex items-center space-x-2">
                    <button type="button" class="decrement bg-gray-200 px-2 rounded">-</button>
                    <input type="number" value="0" min="0" 
                      class="jumlah w-12 text-center border rounded" 
                      data-harga="{{ $layanan->harga_per_hari }}"
                      data-id="{{ $layanan->id_paket }}"
                      data-nama="{{ $layanan->nama_paket }}"
                      name="addon_{{ $layanan->id_paket }}">
                    <button type="button" class="increment bg-gray-200 px-2 rounded">+</button>
                  </div>
                </div>
              @empty
                <p class="text-gray-500 text-sm">Tidak ada layanan tambahan tersedia</p>
              @endforelse
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
        <a href="{{ url('/user/dashboard') }}" class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">Kembali</a>
        <button type="submit" class="px-4 py-2 rounded bg-[#F2784B] hover:bg-[#e0673d] text-white">Lanjut ke Pembayaran</button>
      </div>
    </form>
  </div>

@push('scripts')
  <script>
    const ringkasan = document.getElementById('ringkasanBiaya');
    const checkInDateInput = document.getElementById('checkInDate');
    const checkOutDateInput = document.getElementById('checkOutDate');
    const jumlahInputs = document.querySelectorAll('.jumlah');
    const incrementBtns = document.querySelectorAll('.increment');
    const decrementBtns = document.querySelectorAll('.decrement');
    const packageTypeInput = document.getElementById('packageType');
    const packageIdInput = document.getElementById('packageId');
    let selectedPaket = null;

    function pilihPaket(el) {
      document.querySelectorAll('[data-harga]').forEach(card => card.classList.remove('border-4', 'border-orange-400'));
      el.classList.add('border-4', 'border-orange-400');
      selectedPaket = {
        id: el.dataset.id,
        nama: el.dataset.nama,
        harga: parseInt(el.dataset.harga)
      };
      packageTypeInput.value = selectedPaket.nama;
      packageIdInput.value = selectedPaket.id;
      updateRingkasan();
    }

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

      // Paket utama
      if (selectedPaket) {
        const subtotal = selectedPaket.harga * jumlahHari;
        total += subtotal;
        html += `<div class="flex justify-between mb-1">
          <span>${selectedPaket.nama} (x${jumlahHari} hari)</span>
          <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
        </div>`;
      }

      // Add-on
      jumlahInputs.forEach(input => {
        const jumlah = parseInt(input.value);
        const harga = parseInt(input.dataset.harga);
        const nama = input.dataset.nama || input.closest('div.flex.justify-between').querySelector('span').textContent;
        if (jumlah > 0) {
          const subtotal = harga * jumlah;
          total += subtotal;
          html += `<div class="flex justify-between mb-1">
            <span>${nama} (x${jumlah})</span>
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
@endpush
@endsection

