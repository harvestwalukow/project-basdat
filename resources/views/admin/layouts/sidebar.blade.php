<!-- Sidebar -->
<aside class="w-64 bg-gradient-to-b from-orange-400 to-orange-600 text-white flex flex-col">
  <div class="p-6 text-2xl font-bold border-b border-white/30">
    @if(session('user_role') === 'admin')
      PAWS HOTEL OWNER
    @elseif(session('user_role') === 'staff')
      PAWS HOTEL STAFF
    @else
      PAWS HOTEL ADMIN
    @endif
  </div>
  <nav class="flex-1 px-4 py-6 space-y-2">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-house w-5 mr-3"></i>
      DASHBOARD
    </a>
    <a href="{{ route('admin.booking') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.booking') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-clipboard-list w-5 mr-3"></i>
      PENITIPAN
    </a>
    <a href="{{ route('admin.pets') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.pets') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-paw w-5 mr-3"></i>
      HEWAN
    </a>
    <a href="{{ route('admin.rooms') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.rooms') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-camera w-5 mr-3"></i>
      UPDATE KONDISI
    </a>
    <a href="{{ route('admin.service') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.service') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-box w-5 mr-3"></i>
      PAKET LAYANAN
    </a>
    <a href="{{ route('admin.payments') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.payments') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-credit-card w-5 mr-3"></i>
      PEMBAYARAN
    </a>

    @if(session('user_role') === 'admin')
    <!-- Admin Only Menu -->
    <hr class="my-2 border-white/30">
    <a href="{{ route('admin.staff') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.staff') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-users w-5 mr-3"></i>
      KARYAWAN
    </a>
    <a href="{{ route('admin.reports') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.reports') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold">
      <i class="fa-solid fa-chart-bar w-5 mr-3"></i>
      LAPORAN
    </a>
    @endif
  </nav>
  <div class="p-4 border-t border-white/30">
    <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-white hover:bg-orange-500/50 rounded-md">
      <i class="fa-solid fa-right-from-bracket w-5 mr-3"></i>
      LOGOUT
    </a>
  </div>
</aside>
