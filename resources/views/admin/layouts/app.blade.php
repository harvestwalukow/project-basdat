<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard - Paws Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    .sidebar-minimized {
      width: 80px !important;
    }
    .sidebar-text {
      transition: opacity 0.2s, transform 0.2s;
      white-space: nowrap;
    }
    .sidebar-minimized .sidebar-text {
      opacity: 0;
      transform: scale(0);
      width: 0;
      overflow: hidden;
    }
    .sidebar-minimized .sidebar-header {
      justify-content: center !important;
      padding-left: 0 !important;
      padding-right: 0 !important;
    }
    aside {
      transition: width 0.3s ease;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

  <div class="flex h-screen">
    @include('admin.layouts.sidebar')

    <!-- Main Content -->
    <main id="mainContent" class="flex-1 p-8 overflow-y-auto">
      @yield('content')
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('sidebar-minimized');
      
      // Simpan state ke localStorage
      if (sidebar.classList.contains('sidebar-minimized')) {
        localStorage.setItem('sidebarState', 'minimized');
      } else {
        localStorage.setItem('sidebarState', 'expanded');
      }
    }

    // Load state saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarState = localStorage.getItem('sidebarState');
      if (sidebarState === 'minimized') {
        document.getElementById('sidebar').classList.add('sidebar-minimized');
      }
    });
  </script>

  @stack('scripts')

</body>
</html>
