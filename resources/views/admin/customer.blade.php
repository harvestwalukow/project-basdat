@extends('admin.layouts.app')

@section('content')
  <div class="flex flex-col h-full">
    <!-- Header -->
    <header class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold">PENGGUNA</h1>
        <p class="text-gray-600">Daftar semua pengguna terdaftar</p>
      </div>
      <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span>Tambah Pengguna</span>
      </button>
    </header>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md col-span-1">
            <h3 class="text-lg font-semibold text-gray-600">Total Pengguna</h3>
            <p class="text-3xl font-bold mt-2">45</p>
            <p class="text-sm text-green-500">+5 bulan ini</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md col-span-1">
            <h3 class="text-lg font-semibold text-gray-600">Pet Owner</h3>
            <p class="text-3xl font-bold mt-2">38</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md col-span-1">
            <h3 class="text-lg font-semibold text-gray-600">Staff</h3>
            <p class="text-3xl font-bold mt-2">7</p>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-6">
      <input id="searchInput" type="text" placeholder="Cari nama pengguna, email, atau ID" class="w-full px-4 py-2 border rounded-lg">
    </div>
    
    <!-- User Table -->
    <div class="flex-1 bg-white rounded-lg shadow-md overflow-hidden">
      <div class="px-6 py-4 border-b">
        <h3 class="font-semibold">DAFTAR PENGGUNA</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-max">
          <thead class="bg-gray-50 text-left text-sm text-gray-600">
            <tr>
              <th class="p-4">Pengguna</th>
              <th class="p-4">Kontak</th>
              <th class="p-4">Role</th>
              <th class="p-4 text-center">Total Penitipan</th>
              <th class="p-4 text-right">Total Pengeluaran</th>
              <th class="p-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody id="userTable">
            <tr class="border-b hover:bg-gray-50">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold">SI</div>
                  <div>
                    <p class="font-medium">Siti Indah</p>
                    <p class="text-sm text-gray-500">ID: USR-001</p>
                  </div>
                </div>
              </td>
              <td class="p-4">
                <p>indah@mail.com</p>
                <p class="text-sm text-gray-500">081234567890</p>
              </td>
              <td class="p-4"><span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Pet Owner</span></td>
              <td class="p-4 text-center">5</td>
              <td class="p-4 text-right">Rp 5.500.000</td>
              <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold">FH</div>
                  <div>
                    <p class="font-medium">Fajar Hidayat</p>
                     <p class="text-sm text-gray-500">ID: CUST-002</p>
                  </div>
                </div>
              </td>
              <td class="p-4">
                <p>fajar@mail.com</p>
                <p class="text-sm text-gray-500">082345678901</p>
              </td>
              <td class="p-4">1 (Anjing)</td>
              <td class="p-4 text-center">2</td>
              <td class="p-4 text-right">Rp 2.100.000</td>
              <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold">HW</div>
                  <div>
                    <p class="font-medium">Heru Wasesa</p>
                     <p class="text-sm text-gray-500">ID: CUST-003</p>
                  </div>
                </div>
              </td>
              <td class="p-4">
                <p>heru@mail.com</p>
                <p class="text-sm text-gray-500">083456789012</p>
              </td>
              <td class="p-4">1 (Kucing)</td>
              <td class="p-4 text-center">8</td>
              <td class="p-4 text-right">Rp 9.250.000</td>
              <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span></td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold">MF</div>
                   <div>
                    <p class="font-medium">Mega Lestari</p>
                     <p class="text-sm text-gray-500">ID: CUST-004</p>
                  </div>
                </div>
              </td>
              <td class="p-4">
                <p>mega@mail.com</p>
                <p class="text-sm text-gray-500">084567890123</p>
              </td>
              <td class="p-4">3 (2 Anjing, 1 Kucing)</td>
              <td class="p-4 text-center">12</td>
              <td class="p-4 text-right">Rp 15.800.000</td>
              <td class="p-4 text-center"><span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Non-Aktif</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const tableRows = document.querySelectorAll("#userTable tr");

  searchInput.addEventListener("keyup", function () {
    const filter = searchInput.value.toLowerCase();

    tableRows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(filter) ? "" : "none";
    });
  });
});
</script>
