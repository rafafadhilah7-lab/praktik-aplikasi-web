<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'SIPINJAM-LAB') - Sistem Peminjaman Alat Laboratorium</title>

  <!-- Google Fonts: Plus Jakarta Sans & Space Grotesk -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-[#fcf9fa] text-[#1e293b]">

  <!-- Top Navigation Header -->
  <header class="site-header">
    <div class="site-header-inner">
      <!-- Brand Logo -->
      <div class="flex items-center gap-3">
        <a href="{{ route('alat.beranda') }}" class="flex items-center gap-2.5 text-decoration-none group">
          <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-pink-600 to-rose-400 flex items-center justify-center text-white shadow-md shadow-pink-200 group-hover:scale-105 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
          </div>
          <div>
            <div class="font-bold tracking-tight text-lg text-slate-900 group-hover:text-pink-600 transition-colors">
              SIPINJAM<span class="text-pink-600">-LAB</span>
            </div>
            <div class="text-[10px] uppercase font-semibold tracking-wider text-pink-500">Sistem Peminjaman Alat</div>
          </div>
        </a>
      </div>

      <!-- Navigation Links -->
      <nav class="hidden md:flex items-center gap-1">
        <a href="{{ route('alat.beranda') }}"
           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('alat.*') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
          Katalog Alat
        </a>

        @auth
          @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Dashboard Admin
            </a>
            <a href="{{ route('admin.alat.index') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.alat.*') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Kelola Alat
            </a>
            <a href="{{ route('peminjaman.index') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('peminjaman.*') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Semua Transaksi
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Pengguna
            </a>
          @elseif(Auth::user()->isLaboran())
            <a href="{{ route('laboran.dashboard') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('laboran.dashboard') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Dashboard Laboran
            </a>
            <a href="{{ route('peminjaman.index') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('peminjaman.*') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Kelola Peminjaman
            </a>
          @elseif(Auth::user()->isPeminjam())
            <a href="{{ route('peminjam.dashboard') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('peminjam.dashboard') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Dashboard Peminjam
            </a>
            <a href="{{ route('peminjam.riwayat') }}"
               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('peminjam.riwayat') ? 'text-pink-600 bg-pink-50 font-semibold' : 'text-slate-600 hover:text-pink-600 hover:bg-pink-50/50' }}">
              Riwayat Saya
            </a>
          @endif
        @endauth
      </nav>

      <!-- Right Auth Actions -->
      <div class="flex items-center gap-3">
        @guest
          <a href="{{ route('login') }}"
             class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-pink-600 transition-colors">
            Masuk
          </a>
          <a href="{{ route('register') }}"
             class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-pink-600 to-rose-500 hover:from-pink-700 hover:to-rose-600 shadow-sm shadow-pink-200 transition-all hover:-translate-y-0.5">
            Daftar Akun
          </a>
        @else
          <div class="flex items-center gap-3">
            <a href="{{ route('profil.edit') }}"
               class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-pink-100 bg-pink-50/50 hover:bg-pink-100/60 transition-colors text-left"
               title="Kelola Profil">
              <div class="w-7 h-7 rounded-full bg-pink-600 text-white flex items-center justify-center font-bold text-xs">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </div>
              <div class="text-xs">
                <div class="font-semibold text-slate-800 leading-tight">{{ Str::limit(Auth::user()->name, 15) }}</div>
                <div class="text-[10px] text-pink-600 font-bold uppercase tracking-wider">{{ Auth::user()->role_label }}</div>
              </div>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="inline m-0">
              @csrf
              <button type="submit"
                      class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 transition-all cursor-pointer"
                      title="Keluar dari akun">
                Logout
              </button>
            </form>
          </div>
        @endguest
      </div>
    </div>
  </header>

  <!-- Flash Notification Banners -->
  <div class="max-w-7xl mx-auto w-full px-4 pt-4">
    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50/90 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="font-medium">{{ session('success') }}</div>
      </div>
    @endif

    @if (session('error'))
      <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50/90 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="font-medium">{{ session('error') }}</div>
      </div>
    @endif

    @if (session('info'))
      <div class="mb-4 rounded-xl border border-sky-200 bg-sky-50/90 p-4 text-sm text-sky-800 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-sky-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="font-medium">{{ session('info') }}</div>
      </div>
    @endif
  </div>

  <!-- Main Application Content -->
  <main class="container-page flex-1">
    @yield('content')
  </main>

  <!-- Site Footer -->
  <footer class="site-footer mt-auto">
    <div class="site-footer-inner">
      <div class="flex items-center gap-2">
        <span class="font-bold text-slate-800">SIPINJAM-LAB</span>
        <span>&bull;</span>
        <span>Praktik Aplikasi Web (INF60295)</span>
      </div>
      <div>
        <span>Sistem Informasi Peminjaman Alat Laboratorium Terpadu &copy; {{ date('Y') }}</span>
      </div>
    </div>
  </footer>

  @stack('scripts')
</body>
</html>