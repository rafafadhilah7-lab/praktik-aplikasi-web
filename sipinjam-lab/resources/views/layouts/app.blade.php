<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'SIPINJAM-LAB') - Sistem Peminjaman Alat Laboratorium</title>

  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#f5f6f8] text-[#202938] antialiased">

@auth
  <!-- ================= AUTHENTICATED ENTERPRISE LAYOUT ================= -->
  <div class="min-h-full flex">
    <!-- Sidebar Kiri Navy Gelap (#172235) -->
    <aside id="appSidebar" class="sidebar-container fixed inset-y-0 left-0 z-40 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-200 ease-in-out">
      <!-- Logo & Brand Header -->
      <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 text-decoration-none">
          <div class="w-8 h-8 rounded-md bg-[#2563eb] flex items-center justify-center text-white shadow-sm flex-shrink-0">
            <!-- Lucide Flask / Lab Icon -->
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
          </div>
          <div>
            <div class="font-bold text-sm text-white tracking-tight leading-none">SIPINJAM-LAB</div>
            <div class="text-[10px] text-slate-400 font-medium tracking-wider uppercase mt-1">Sistem Laboratorium</div>
          </div>
        </a>
      </div>

      <!-- Navigation Items Berdasarkan Role -->
      <div class="flex-1 overflow-y-auto py-3 space-y-4">
        @if(Auth::user()->isAdmin())
          <!-- Role: Administrator -->
          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect width="7" height="9" x="3" y="3" rx="1"/>
                <rect width="7" height="5" x="14" y="3" rx="1"/>
                <rect width="7" height="9" x="14" y="12" rx="1"/>
                <rect width="7" height="5" x="3" y="16" rx="1"/>
              </svg>
              <span>Dashboard Admin</span>
            </a>
          </div>

          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Inventaris & Alat</div>
            <a href="{{ route('admin.alat.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.alat.index') || request()->routeIs('admin.alat.edit') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
              </svg>
              <span>Manajemen Alat</span>
            </a>
            <a href="{{ route('admin.alat.create') }}"
               class="sidebar-link {{ request()->routeIs('admin.alat.create') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
              </svg>
              <span>Tambah Alat</span>
            </a>
            <a href="{{ route('alat.beranda') }}"
               class="sidebar-link {{ request()->routeIs('alat.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
              </svg>
              <span>Katalog Publik</span>
            </a>
          </div>

          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Transaksi Peminjaman</div>
            <a href="{{ route('peminjaman.index') }}"
               class="sidebar-link {{ request()->routeIs('peminjaman.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span>Seluruh Transaksi</span>
            </a>
          </div>

          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Sistem & Akses</div>
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 00-3-3.87m-3-12a4 4 0 010 7.75"/>
              </svg>
              <span>Manajemen Pengguna</span>
            </a>
            <a href="{{ route('profil.edit') }}"
               class="sidebar-link {{ request()->routeIs('profil.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
              </svg>
              <span>Pengaturan Akun</span>
            </a>
          </div>

        @elseif(Auth::user()->isLaboran())
          <!-- Role: Petugas Laboran -->
          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Menu Utama</div>
            <a href="{{ route('laboran.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('laboran.dashboard') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect width="7" height="9" x="3" y="3" rx="1"/>
                <rect width="7" height="5" x="14" y="3" rx="1"/>
                <rect width="7" height="9" x="14" y="12" rx="1"/>
                <rect width="7" height="5" x="3" y="16" rx="1"/>
              </svg>
              <span>Dashboard Laboran</span>
            </a>
          </div>

          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Operasional Lab</div>
            <a href="{{ route('peminjaman.index') }}"
               class="sidebar-link {{ request()->routeIs('peminjaman.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span>Kelola Peminjaman</span>
            </a>
            <a href="{{ route('admin.alat.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.alat.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
              </svg>
              <span>Inventaris Alat</span>
            </a>
            <a href="{{ route('alat.beranda') }}"
               class="sidebar-link {{ request()->routeIs('alat.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
              </svg>
              <span>Katalog Alat</span>
            </a>
          </div>

          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Pengaturan</div>
            <a href="{{ route('profil.edit') }}"
               class="sidebar-link {{ request()->routeIs('profil.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
              </svg>
              <span>Profil Petugas</span>
            </a>
          </div>

        @else
          <!-- Role: Peminjam (Mahasiswa) -->
          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Portal Akademik</div>
            <a href="{{ route('peminjam.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('peminjam.dashboard') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect width="7" height="9" x="3" y="3" rx="1"/>
                <rect width="7" height="5" x="14" y="3" rx="1"/>
                <rect width="7" height="9" x="14" y="12" rx="1"/>
                <rect width="7" height="5" x="3" y="16" rx="1"/>
              </svg>
              <span>Dashboard Mahasiswa</span>
            </a>
            <a href="{{ route('peminjam.riwayat') }}"
               class="sidebar-link {{ request()->routeIs('peminjam.riwayat') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>Riwayat Pengajuan</span>
            </a>
          </div>

          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Layanan Lab</div>
            <a href="{{ route('alat.beranda') }}"
               class="sidebar-link {{ request()->routeIs('alat.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
              </svg>
              <span>Cari & Pinjam Alat</span>
            </a>
          </div>

          <div class="sidebar-nav-group">
            <div class="sidebar-section-title">Pengaturan</div>
            <a href="{{ route('profil.edit') }}"
               class="sidebar-link {{ request()->routeIs('profil.*') ? 'is-active' : '' }}">
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
              </svg>
              <span>Profil Pengguna</span>
            </a>
          </div>
        @endif
      </div>

      <!-- User Profile Box & Logout -->
      <div class="p-3 border-t border-[#233249]">
        <div class="flex items-center justify-between p-2 rounded-md bg-[#131b2a]">
          <div class="flex items-center gap-2.5 overflow-hidden">
            <div class="w-7 h-7 rounded bg-[#2563eb] text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
              <div class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</div>
              <div class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role_label }}</div>
            </div>
          </div>
          <form action="{{ route('logout') }}" method="POST" class="inline m-0">
            @csrf
            <button type="submit"
                    class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-[#1e2d45] rounded transition-colors"
                    title="Keluar dari sistem">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
              </svg>
            </button>
          </form>
        </div>
      </div>
    </aside>

    <!-- Overlay Mobile -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden md:hidden"></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#f5f6f8]">
      <!-- Topbar Header Putih (#ffffff) -->
      <header class="topbar-header">
        <div class="flex items-center gap-3">
          <!-- Mobile Sidebar Toggle -->
          <button type="button"
                  onclick="toggleSidebar()"
                  class="md:hidden p-1.5 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
          </button>

          <!-- Breadcrumb -->
          <nav class="breadcrumb-nav">
            <a href="{{ route('dashboard') }}" class="font-medium text-slate-500 hover:text-blue-600">SIPINJAM-LAB</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700 font-semibold truncate">@yield('title', 'Sistem Informasi Peminjaman')</span>
          </nav>
        </div>

        <!-- Topbar Right Info -->
        <div class="flex items-center gap-4">
          <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500 font-medium">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Server Aktif &bull; {{ date('d M Y') }}</span>
          </div>

          <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

          <a href="{{ route('profil.edit') }}" class="flex items-center gap-2 text-xs font-semibold text-slate-700 hover:text-blue-600 transition-colors">
            <span>{{ Auth::user()->name }}</span>
            <span class="badge badge-neutral text-[10px]">{{ Auth::user()->role_label }}</span>
          </a>
        </div>
      </header>

      <!-- Alert Notifikasi Flash -->
      <div class="px-6 pt-4 max-w-[1400px] w-full mx-auto">
        @if (session('success'))
          <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="font-medium">{{ session('success') }}</div>
          </div>
        @endif

        @if (session('error'))
          <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-800 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="font-medium">{{ session('error') }}</div>
          </div>
        @endif

        @if (session('info'))
          <div class="mb-4 rounded-md border border-sky-200 bg-sky-50 px-4 py-3 text-xs text-sky-800 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-sky-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="font-medium">{{ session('info') }}</div>
          </div>
        @endif
      </div>

      <!-- Main Content Page -->
      <main class="content-area flex-1">
        @yield('content')
      </main>

      <!-- Clean Minimalist Footer -->
      <footer class="border-t border-[#e5e7eb] bg-white py-3 px-6 text-xs text-slate-500">
        <div class="max-w-[1400px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
          <div>SIPINJAM-LAB &bull; Sistem Informasi Peminjaman Alat Laboratorium</div>
          <div>Universitas / Institusi Pendidikan &copy; {{ date('Y') }}</div>
        </div>
      </footer>
    </div>
  </div>

@else
  <!-- ================= GUEST / PUBLIC LAYOUT ================= -->
  <div class="min-h-full flex flex-col bg-[#f5f6f8]">
    <!-- Topbar Navy Enterprise -->
    <header class="h-14 bg-[#172235] text-white px-6 flex items-center justify-between border-b border-[#233249] sticky top-0 z-40">
      <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 rounded-md bg-[#2563eb] flex items-center justify-center text-white">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
          </svg>
        </div>
        <div>
          <a href="{{ route('alat.beranda') }}" class="font-bold text-sm tracking-tight text-white hover:text-blue-400 transition-colors">
            SIPINJAM-LAB
          </a>
        </div>
      </div>

      <nav class="flex items-center gap-3">
        <a href="{{ route('alat.beranda') }}" class="text-xs font-medium text-slate-300 hover:text-white px-2.5 py-1.5 rounded transition-colors">
          Katalog Alat
        </a>
        <a href="{{ route('login') }}" class="text-xs font-semibold text-white hover:text-blue-400 px-3 py-1.5 rounded transition-colors">
          Masuk
        </a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
          Registrasi Akun
        </a>
      </nav>
    </header>

    <!-- Alert Notifikasi Flash untuk Tamu -->
    <div class="px-6 pt-4 max-w-4xl w-full mx-auto">
      @if (session('success'))
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-800">
          {{ session('error') }}
        </div>
      @endif

      @if (session('info'))
        <div class="mb-4 rounded-md border border-sky-200 bg-sky-50 px-4 py-3 text-xs text-sky-800">
          {{ session('info') }}
        </div>
      @endif
    </div>

    <!-- Konten Utama Tamu -->
    <main class="flex-1 flex flex-col justify-center py-6 px-4">
      @yield('content')
    </main>

    <!-- Footer Tamu -->
    <footer class="border-t border-[#e5e7eb] bg-white py-4 px-6 text-xs text-slate-500 text-center">
      SIPINJAM-LAB &bull; Sistem Informasi Peminjaman Alat Laboratorium Terpadu &copy; {{ date('Y') }}
    </footer>
  </div>
@endauth

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar && backdrop) {
      const isClosed = sidebar.classList.contains('-translate-x-full');
      if (isClosed) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }
    }
  }
</script>

@stack('scripts')
</body>
</html>