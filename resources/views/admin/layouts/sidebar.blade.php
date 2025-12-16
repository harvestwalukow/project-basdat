<!-- Sidebar -->
<aside id="sidebar" class="w-64 sidebar-expanded bg-gradient-to-b from-orange-400 to-orange-600 text-white flex flex-col relative transition-all duration-300">
  <!-- Toggle Button -->

  <!-- Header & Toggle -->
<style>
  /* Toggle Button Logic */
  .sidebar-minimized #toggleIcon { display: none; }
  .sidebar-minimized #toggleLogo { display: block; }
  /* On Hover in Minimized State: Revert to Icon */
  .sidebar-minimized #sidebarToggleBtn:hover #toggleIcon { display: block; }
  .sidebar-minimized #sidebarToggleBtn:hover #toggleLogo { display: none; }
</style>

  <!-- Header & Toggle -->
  <div class="sidebar-header flex items-center justify-between px-6 py-4 border-b border-white/30 transition-all duration-300">
    <div class="sidebar-text text-2xl font-bold whitespace-nowrap overflow-hidden flex items-center gap-3">
      <img src="{{ asset('img/logo3.png') }}" alt="Logo" class="w-8 h-8 object-contain">
      <span>
      @if(session('user_role') === 'admin')
        OWNER
      @elseif(session('user_role') === 'staff')
        STAFF
      @else
        ADMIN
      @endif
      </span>
    </div>
    
    <button id="sidebarToggleBtn" onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-white/20 transition-colors text-white focus:outline-none flex-shrink-0 flex items-center justify-center w-10 h-10">
      <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
        <line x1="9" y1="3" x2="9" y2="21"></line>
      </svg>
      <img id="toggleLogo" src="{{ asset('img/logo3.png') }}" class="w-8 h-8 object-contain hidden" alt="Logo Minimized">
    </button>
  </div>
  <nav class="flex-1 px-4 py-6 space-y-2">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold" title="Dashboard">
      <i class="fa-solid fa-house-chimney w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">DASHBOARD</span>
    </a>
    <a href="{{ route('admin.booking') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.booking') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold" title="Operasional">
      <i class="fa-solid fa-clipboard-check w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">OPERASIONAL</span>
    </a>
    <a href="{{ route('admin.payments') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.payments') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold" title="Transaksi">
      <i class="fa-solid fa-money-bill-transfer w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">TRANSAKSI</span>
    </a>

    <a href="{{ route('admin.rooms') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.rooms') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold" title="Update Kondisi">
      <i class="fa-solid fa-camera-retro w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">UPDATE KONDISI</span>
    </a>
    <a href="{{ route('admin.service') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.service') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold" title="Paket Layanan">
      <i class="fa-solid fa-boxes-stacked w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">PAKET LAYANAN</span>
    </a>

    @if(session('user_role') === 'admin')
    <!-- Admin Only Menu -->
    <hr class="my-2 border-white/30">
    <a href="{{ route('admin.staff') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.staff') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold" title="Karyawan">
      <i class="fa-solid fa-users-line w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">KARYAWAN</span>
    </a>
    <a href="{{ route('admin.reports') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.reports') ? 'bg-white/90 text-orange-700' : 'text-white hover:bg-orange-500/50' }} rounded-md font-semibold" title="Laporan">
      <i class="fa-solid fa-chart-pie w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">LAPORAN</span>
    </a>
    @endif
  </nav>
  <div class="p-4 border-t border-white/30">
    <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-white hover:bg-orange-500/50 rounded-md" title="Logout">
      <i class="fa-solid fa-arrow-right-from-bracket w-5 mr-3 flex-shrink-0"></i>
      <span class="sidebar-text whitespace-nowrap">LOGOUT</span>
    </a>
  </div>
</aside>
