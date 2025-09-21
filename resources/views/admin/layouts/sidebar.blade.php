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
    <a href="{{ route('admin.customer') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.customer') ? 'bg-yellow-400 text-gray-900' : 'text-gray-300 hover:bg-gray-700' }} rounded-md font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
        PENGGUNA
      </a>
  </nav>
  <div class="p-4 border-t border-gray-700">
    <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
      LOGOUT
    </a>
  </div>
</aside>
