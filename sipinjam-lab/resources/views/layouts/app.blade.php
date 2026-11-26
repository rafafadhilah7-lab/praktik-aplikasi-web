
<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIPINJAM-LAB') - Sistem Peminjaman Alat Laboratorium</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <script>
        (function () {
            try {
                if (localStorage.getItem('sipinjam-theme') === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="app-shell">

        <!-- SIDEBAR -->
        <aside class="app-sidebar" id="appSidebar">

            <!-- BRAND -->
            <div class="sidebar-brand">
                <a href="{{ route('alat.beranda') }}" class="flex items-center gap-3">
                    <div class="sidebar-brand-logo">
                        <svg width="23" height="23" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 3h6m-5 0v6.5L4.5 18a2 2 0 001.7 3h11.6a2 2 0 001.7-3L14 9.5V3M8 15h8"/>
                        </svg>
                    </div>

                    <div>
                        <div class="sidebar-brand-name">
                            SIPINJAM-LAB
                        </div>
                        <div class="sidebar-brand-description">
                            Sistem Peminjaman Alat
                        </div>
                    </div>
                </a>
            </div>

            <!-- NAVIGATION -->
            <nav class="sidebar-nav">

                <div class="sidebar-section-label">Menu Utama</div>

                <a href="{{ route('alat.beranda') }}"
                   class="sidebar-link {{ request()->routeIs('alat.*') ? 'sidebar-link-active' : '' }}">
                    <span class="sidebar-link-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 7l9-4 9 4v10l-9 4-9-4V7zm0 0l9 4 9-4m-9 4v10"/>
                        </svg>
                    </span>
                    <span>Katalog Alat</span>
                </a>

                @auth

                    @if(Auth::user()->isAdmin())

                        <div class="sidebar-section-label">Administrasi</div>

                        <a href="{{ route('admin.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M3 3h8v8H3V3zm10 0h8v5h-8V3zm0 7h8v11h-8V10zM3 13h8v8H3v-8z"/>
                                </svg>
                            </span>
                            <span>Dashboard Admin</span>
                        </a>

                        <a href="{{ route('admin.alat.index') }}"
                           class="sidebar-link {{ request()->routeIs('admin.alat.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M4 5h16M4 12h16M4 19h16M8 5v14m8-14v14"/>
                                </svg>
                            </span>
                            <span>Kelola Alat</span>
                        </a>

                        <a href="{{ route('peminjaman.index') }}"
                           class="sidebar-link {{ request()->routeIs('peminjaman.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                            </span>
                            <span>Semua Transaksi</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                </svg>
                            </span>
                            <span>Pengguna</span>
                        </a>

                        <a href="{{ route('laporan.index') }}"
                           class="sidebar-link {{ request()->routeIs('laporan.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M9 17v-6m4 6V7m4 10v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <span>Laporan</span>
                        </a>

                        <a href="{{ route('aktivitas.index') }}"
                           class="sidebar-link {{ request()->routeIs('aktivitas.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <span>Aktivitas</span>
                        </a>

                    @elseif(Auth::user()->isLaboran())

                        <div class="sidebar-section-label">Operasional</div>

                        <a href="{{ route('laboran.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('laboran.dashboard') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M3 3h8v8H3V3zm10 0h8v5h-8V3zm0 7h8v11h-8V10zM3 13h8v8H3v-8z"/>
                                </svg>
                            </span>
                            <span>Dashboard Laboran</span>
                        </a>

                        <a href="{{ route('peminjaman.index') }}"
                           class="sidebar-link {{ request()->routeIs('peminjaman.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                            </span>
                            <span>Kelola Peminjaman</span>
                        </a>

                        <a href="{{ route('laporan.index') }}"
                           class="sidebar-link {{ request()->routeIs('laporan.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M9 17v-6m4 6V7m4 10v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <span>Laporan</span>
                        </a>

                        <a href="{{ route('aktivitas.index') }}"
                           class="sidebar-link {{ request()->routeIs('aktivitas.*') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <span>Aktivitas</span>
                        </a>

                    @elseif(Auth::user()->isPeminjam())

                        <div class="sidebar-section-label">Area Peminjam</div>

                        <a href="{{ route('peminjam.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('peminjam.dashboard') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M3 3h8v8H3V3zm10 0h8v5h-8V3zm0 7h8v11h-8V10zM3 13h8v8H3v-8z"/>
                                </svg>
                            </span>
                            <span>Dashboard Peminjam</span>
                        </a>

                        <a href="{{ route('peminjam.riwayat') }}"
                           class="sidebar-link {{ request()->routeIs('peminjam.riwayat') ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <span>Riwayat Saya</span>
                        </a>

                    @endif

                    @php
                        $unreadCount = \Illuminate\Support\Facades\Schema::hasTable('notifications')
                            ? Auth::user()->unreadNotifications()->count()
                            : 0;
                    @endphp

                    <div class="sidebar-section-label">Akun</div>

                    <a href="{{ route('notifikasi.index') }}"
                       class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'sidebar-link-active' : '' }}">
                        <span class="sidebar-link-icon">
                            <svg width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M15 17h5l-1.4-1.4A2 2 0 0118 14.17V11a6 6 0 10-12 0v3.17a2 2 0 01-.6 1.43L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </span>
                        <span class="flex-1">Notifikasi</span>
                        @if($unreadCount > 0)
                            <span class="badge badge-info text-[10px]">{{ $unreadCount }}</span>
                        @endif
                    </a>

                @endauth
            </nav>

            <!-- SIDEBAR USER -->
            @auth
                <div class="sidebar-user">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="sidebar-user-name">
                            {{ Str::limit(Auth::user()->name, 20) }}
                        </div>
                        <div class="sidebar-user-role">
                            {{ Auth::user()->role_label }}
                        </div>
                    </div>
                </div>
            @endauth

        </aside>

        <!-- MOBILE SIDEBAR OVERLAY -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- MAIN CONTENT AREA -->
        <div class="app-content">

            <!-- TOPBAR -->
            <header class="app-topbar">

                <div class="flex items-center gap-3">
                    <button type="button"
                            class="sidebar-toggle"
                            id="sidebarToggle"
                            aria-label="Buka navigasi"
                            aria-controls="appSidebar"
                            aria-expanded="false"
                            data-sidebar-toggle>
                        <svg width="21" height="21" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div>
                        <div class="topbar-title">
                            @yield('title', 'Dashboard')
                        </div>
                        <div class="topbar-subtitle">
                            Sistem Peminjaman Alat Laboratorium Terpadu
                        </div>
                    </div>
                </div>

                <div class="topbar-actions">

                    <!-- THEME TOGGLE -->
                    <button type="button"
                            class="theme-toggle"
                            id="themeToggle"
                            data-theme-toggle
                            aria-label="Ganti tema"
                            title="Ganti Light/Dark Mode">
                        <svg data-theme-icon="light" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="4"/>
                            <path stroke-linecap="round" stroke-width="1.8"
                                  d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3l1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3l1.42-1.42"/>
                        </svg>

                        <svg data-theme-icon="dark" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M20.5 15.5A8.5 8.5 0 018.5 3.5 8.5 8.5 0 1020.5 15.5z"/>
                        </svg>
                    </button>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                            Masuk
                        </a>

                        <a href="{{ route('register') }}" class="btn btn-sm">
                            Daftar
                        </a>
                    @else
                        <a href="{{ route('profil.edit') }}"
                           class="btn btn-secondary btn-sm">
                            Profil
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                Logout
                            </button>
                        </form>
                    @endguest

                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="app-main">
                <div class="app-main-inner">

                    <!-- FLASH NOTIFICATIONS -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            <div class="flex items-start gap-3">
                                <span aria-hidden="true">✓</span>
                                <div>{{ session('success') }}</div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-error">
                            <div class="flex items-start gap-3">
                                <span aria-hidden="true">!</span>
                                <div>{{ session('error') }}</div>
                            </div>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info">
                            <div class="flex items-start gap-3">
                                <span aria-hidden="true">i</span>
                                <div>{{ session('info') }}</div>
                            </div>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </main>

            <!-- FOOTER -->
            <footer class="site-footer mt-auto">
                <div class="site-footer-inner">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-bold">SIPINJAM-LAB</span>
                        <span>&bull;</span>
                        <span>Praktik Aplikasi Web (INF60295)</span>
                    </div>

                    <div class="mt-2">
                        Sistem Informasi Peminjaman Alat Laboratorium Terpadu
                        &copy; {{ date('Y') }}
                    </div>
                </div>
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
