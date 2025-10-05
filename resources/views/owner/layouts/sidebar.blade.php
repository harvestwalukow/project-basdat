<!-- Sidebar -->
<aside class="w-64 bg-gray-800 text-white flex flex-col">
  <div class="p-6 text-2xl font-bold border-b border-gray-700">
    PAWS HOTEL OWNER
  </div>
  <nav class="flex-1 px-4 py-6 space-y-2">
    {{-- Dashboard --}}
    <a href="{{ route('owner.dashboard') }}" 
       class="flex items-center px-4 py-2 {{ request()->routeIs('owner.dashboard') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m-4-4h8"/>
      </svg>
      DASHBOARD
    </a>

    {{-- Reservasi --}}
    <a href="{{ route('owner.reservations') }}" 
       class="flex items-center px-4 py-2 {{ request()->routeIs('owner.reservations') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 4h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
      RESERVASI
    </a>

    {{-- Keuangan --}}
    <a href="{{ route('owner.finance') }}" 
       class="flex items-center px-4 py-2 {{ request()->routeIs('owner.finance') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.105 0-2 .672-2 1.5S10.895 11 12 11s2 .672 2 1.5S13.105 14 12 14m0 0v1m0-7V7m-7 9h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
      </svg>
      KEUANGAN
    </a>

    {{-- Hewan --}}
    <a href="{{ route('owner.pets') }}" 
       class="flex items-center px-4 py-2 {{ request()->routeIs('owner.pets') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.879 6.196M9 13h6m-6 4h6"/>
      </svg>
      HEWAN
    </a>

    {{-- Layanan --}}
    <a href="{{ route('owner.services') }}" 
       class="flex items-center px-4 py-2 {{ request()->routeIs('owner.services') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L12 19.25 14.25 17M12 4v15"/>
      </svg>
      LAYANAN
    </a>

    {{-- Staff --}}
    <a href="{{ route('owner.staff') }}" 
       class="flex items-center px-4 py-2 {{ request()->routeIs('owner.staff') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/>
      </svg>
      STAFF
    </a>

    {{-- Laporan --}}
    <a href="{{ route('owner.reports') }}" 
       class="flex items-center px-4 py-2 {{ request()->routeIs('owner.reports') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h13v6M9 13V7h13v6m-7-2h-6"/>
      </svg>
      LAPORAN
    </a>
  </nav>

  {{-- Logout --}}
  <div class="p-4 border-t border-gray-700">
    <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-9V7"/>
      </svg>
      LOGOUT
    </a>
  </div>
</aside>
