<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pembayaran</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-[#FEFBF7] text-[#333333]">

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-8">
      <h1 class="text-4xl mb-4 font-bold text-gray-800">Pembayaran Reservasi</h1>
      <p class="text-gray-700">
        Selesaikan pembayaran Anda untuk konfirmasi reservasi.
      </p>
    </div>

    <form action="{{ url('/pembayaran') }}" method="POST" class="space-y-8">
        @csrf
        @method('POST')

        <div class="bg-white shadow-md rounded-lg border border-yellow-200">
          <div class="border-b bg-yellow-50 px-4 py-3 rounded-t-lg">
            <h2 class="font-semibold text-gray-800">Ringkasan Reservasi</h2>
          </div>
          <div class="p-4">
            <ul class="space-y-2 text-gray-700">
              <li class="flex justify-between items-center border-b pb-2">
                <span class="font-medium">Nama Pemilik:</span>
                <span id="summaryOwnerName" class="text-right">Nama Anda</span>
              </li>
              <li class="flex justify-between items-center border-b pb-2">
                <span class="font-medium">Nama Hewan:</span>
                <span id="summaryPetName" class="text-right">Nama Hewan</span>
              </li>
              <li class="flex justify-between items-center border-b pb-2">
                <span class="font-medium">Jenis Paket:</span>
                <span id="summaryPackage" class="text-right">Paket Premium</span>
              </li>
              <li class="flex justify-between items-center border-b pb-2">
                <span class="font-medium">Tanggal Menginap:</span>
                <span id="summaryDates" class="text-right">21 Sep 2025 - 23 Sep 2025</span>
              </li>
              <li class="flex justify-between items-center font-bold text-lg pt-2">
                <span>Total Biaya:</span>
                <span id="totalCost" class="text-[#F2784B]">Rp 500.000</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="bg-white shadow-md rounded-lg border border-yellow-200">
          <div class="border-b bg-yellow-50 px-4 py-3 rounded-t-lg">
            <h2 class="font-semibold text-gray-800">Metode Pembayaran</h2>
            <p class="text-sm text-gray-500">Pilih metode pembayaran yang Anda inginkan</p>
          </div>
          <div class="p-4 space-y-4">
            <div class="border rounded-lg p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition-colors duration-200" onclick="selectPayment('transfer')">
              <div class="flex items-center space-x-4">
                <input type="radio" id="transfer" name="paymentMethod" value="transfer" class="h-4 w-4 text-[#F2784B] focus:ring-[#F2784B]" required>
                <label for="transfer" class="font-medium text-gray-700">Transfer Bank</label>
              </div>
              <div class="flex space-x-2">
                <img src="https://via.placeholder.com/60x20.png?text=BCA" alt="BCA" class="h-5">
                <img src="https://via.placeholder.com/60x20.png?text=Mandiri" alt="Mandiri" class="h-5">
              </div>
            </div>

            <div class="border rounded-lg p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition-colors duration-200" onclick="selectPayment('qris')">
              <div class="flex items-center space-x-4">
                <input type="radio" id="qris" name="paymentMethod" value="qris" class="h-4 w-4 text-[#F2784B] focus:ring-[#F2784B]">
                <label for="qris" class="font-medium text-gray-700">QRIS</label>
              </div>
              <img src="https://via.placeholder.com/60x20.png?text=QRIS" alt="QRIS" class="h-5">
            </div>

            <div class="border rounded-lg p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition-colors duration-200" onclick="selectPayment('e-wallet')">
              <div class="flex items-center space-x-4">
                <input type="radio" id="e-wallet" name="paymentMethod" value="e-wallet" class="h-4 w-4 text-[#F2784B] focus:ring-[#F2784B]">
                <label for="e-wallet" class="font-medium text-gray-700">E-Wallet</label>
              </div>
              <div class="flex space-x-2">
                <img src="https://via.placeholder.com/40x20.png?text=Gopay" alt="Gopay" class="h-5">
                <img src="https://via.placeholder.com/40x20.png?text=OVO" alt="OVO" class="h-5">
                <img src="https://via.placeholder.com/40x20.png?text=Dana" alt="Dana" class="h-5">
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white shadow-md rounded-lg border border-yellow-200">
          <div class="border-b bg-yellow-50 px-4 py-3 rounded-t-lg">
            <h2 class="font-semibold text-gray-800">Keterangan Tambahan</h2>
          </div>
          <div class="p-4">
            <p class="text-sm text-gray-600">
              Biaya yang tertera sudah termasuk biaya layanan dan pajak. Silakan pilih salah satu metode pembayaran di atas untuk melanjutkan.
            </p>
          </div>
        </div>
        
        <div class="flex justify-end space-x-4 mt-6 border-t pt-4">
          <button 
            type="button" 
            class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100"
            onclick="window.history.back()">
            Kembali
          </button>
          <button 
            type="submit" 
            class="px-4 py-2 rounded bg-[#F2784B] hover:bg-[#e0673d] text-white">
            Bayar Sekarang
          </button>
        </div>

    </form>
  </div>

  <script>
    function selectPayment(id) {
      document.getElementById(id).checked = true;
    }
  </script>

</body>
</html>