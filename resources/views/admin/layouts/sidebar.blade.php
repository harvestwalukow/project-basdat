<!-- Sidebar -->
<aside class="w-64 bg-gray-800 text-white flex flex-col">
  <div class="p-6 text-2xl font-bold border-b border-gray-700">
    PET HOTEL ADMIN
  </div>
  <nav class="flex-1 px-4 py-6 space-y-2">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
      DASHBOARD
    </a>
    <a href="{{ route('admin.booking') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.booking') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
      PENITIPAN
    </a>
    <a href="{{ route('admin.pets') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.pets') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.247-7.99a5.15 5.15 0 104.242 0M17.247 9.753a5.15 5.15 0 11-4.242 0"></path></svg>
      HEWAN
    </a>
    <a href="{{ route('admin.rooms') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.rooms') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      UPDATE KONDISI
    </a>
    <a href="{{ route('admin.service') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.service') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-1H4a1 1 0 01-1-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5zM10 5H4v3h6V5zm1 4H5v3h6v-3zm1 4H6v3h6v-3z"></path></svg>
      PAKET LAYANAN
    </a>
    <a href="{{ route('admin.payments') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.payments') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 6a2 2 0 00-2 2v2a2 2 0 002 2h12a2 2 0 002-2v-2a2 2 0 00-2-2H4z"></path></svg>
      PEMBAYARAN
    </a>
  </nav>
  <div class="p-4 border-t border-gray-700">
    <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
      LOGOUT
    </a>
  </div>
</aside>
