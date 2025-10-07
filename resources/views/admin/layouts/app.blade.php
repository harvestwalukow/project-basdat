<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard - Pet Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

  <div class="flex h-screen">
    @include('admin.layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
      @yield('content')
    </main>
  </div>

<script>
  @stack('scripts')
</script>

</body>
</html>
