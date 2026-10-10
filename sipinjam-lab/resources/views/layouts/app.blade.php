<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'SIPINJAM-LAB')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Space+Grotesk:wght@500;600&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <header class="site-header">
    <div class="site-header-inner">SIPINJAM-LAB</div>
  </header>
  <main class="container-page">
    @yield('content')
  </main>
  <footer class="site-footer">
    <div class="site-footer-inner">Praktik Aplikasi Web (INF60295) - Sistem Peminjaman Alat Laboratorium</div>
  </footer>
</body>
</html>