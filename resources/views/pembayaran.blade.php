<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Reservasi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#FEFBF7] text-[#333333]">

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
    <div class="bg-white shadow-lg rounded-lg p-6 border border-yellow-200">
      <h1 class="text-3xl font-bold mb-4 text-center text-gray-800">Detail Reservasi</h1>
      <p class="text-center text-gray-600 mb-6">Silakan cek kembali data reservasi sebelum melakukan pembayaran</p>

      <!-- Tabel Data -->
      <div class="overflow-x-auto mb-6">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-300">
          <tbody class="divide-y divide-gray-200 text-sm">
            @foreach($data as $key => $value)
              <tr>
                <td class="px-4 py-2 font-medium capitalize bg-gray-50 w-1/3">
                  {{ str_replace('_',' ',$key) }}
                </td>
                <td class="px-4 py-2">
                  {{ $value }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Ringkasan Harga -->
      @php
        $harga = [
          'basic' => 150000,
          'premium' => 250000,
          'vip' => 400000,
          'daycare' => 80000,
        ];

        $paket = $data['packageType'] ?? null;
        $total = $harga[$paket] ?? 0;
      @endphp

      <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
        <h2 class="font-semibold text-lg text-gray-800 mb-2">Ringkasan Biaya</h2>
        <p>Paket yang dipilih: 
          <span class="font-medium capitalize">{{ $paket }}</span>
        </p>
        <p>Total Biaya: 
          <span class="font-bold text-[#F2784B]">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </p>
      </div>

      <!-- Metode Pembayaran -->
      <div class="card mt-4 bg-white border border-gray-200 rounded-lg shadow p-4 mb-6">
        <h5 class="text-lg font-semibold mb-2 text-gray-800">Pilih Metode Pembayaran</h5>
        <p class="text-gray-500 mb-4">Pilih metode pembayaran yang Anda inginkan</p>

        <div class="form-check mb-3">
            <input class="form-check-input mr-2" type="radio" name="metode_pembayaran" id="transfer" value="Transfer Bank" required>
            <label class="form-check-label" for="transfer">
                <strong>🏦 Transfer Bank</strong><br>
                <small class="text-gray-500">BCA, Mandiri, BNI, BRI</small>
            </label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input mr-2" type="radio" name="metode_pembayaran" id="ewallet" value="E-Wallet">
            <label class="form-check-label" for="ewallet">
                <strong>📱 E-Wallet</strong><br>
                <small class="text-gray-500">GoPay, OVO, DANA, ShopeePay</small>
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input mr-2" type="radio" name="metode_pembayaran" id="cod" value="Cash on Delivery">
            <label class="form-check-label" for="cod">
                <strong>💵 Cash on Delivery</strong><br>
                <small class="text-gray-500">Bayar saat check-in</small>
            </label>
        </div>
      </div>

      <!-- Tombol Navigasi -->
      <div class="flex justify-end space-x-4 border-t pt-4">
        <a href="{{ url('/reservasi') }}" 
           class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">
          edit
        </a>
        <button id="btnBayar" class="px-4 py-2 rounded bg-[#F2784B] hover:bg-[#e0673d] text-white">
          Bayar Sekarang
        </button>
      </div>
    </div>
  </div>

  <!-- Script Popup -->
  <script>
    document.getElementById('btnBayar').addEventListener('click', function() {
      let metode = document.querySelector('input[name="metode_pembayaran"]:checked');
      
      if (!metode) {
        Swal.fire({
          icon: 'warning',
          title: 'Metode belum dipilih',
          text: 'Silakan pilih metode pembayaran terlebih dahulu!',
          confirmButtonColor: '#F2784B'
        });
        return;
      }

      Swal.fire({
        icon: 'success',
        title: 'Pembayaran Berhasil!',
        html: `
          <p class="mb-2">Terima kasih telah melakukan reservasi di <b>PawsHotel</b> 🐾</p>
          <p class="text-sm text-gray-600">Metode pembayaran: <b>${metode.value}</b></p>
          <p class="text-sm text-gray-600">Total yang dibayarkan: <b>Rp {{ number_format($total, 0, ',', '.') }}</b></p>
          <hr class="my-3">
          <p class="text-sm text-gray-500">Detail reservasi dan bukti pembayaran telah dikirim ke email Anda.</p>
        `,
        confirmButtonText: 'OK',
        confirmButtonColor: '#F2784B'
      });
    });
  </script>

</body>
</html>
