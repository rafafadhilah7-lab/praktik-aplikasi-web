<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIPINJAM-LAB') - Sistem Peminjaman Alat Laboratorium</title>

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

<body class="min-h-screen flex flex-col">
    <!-- NAVBAR -->
    <header class="landing-navbar">
        <div class="landing-navbar-inner">
            <a href="{{ route('alat.beranda') }}" class="flex items-center gap-3 shrink-0">
                <div class="sidebar-brand-logo">
                    <svg width="22" height="22" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 3h6m-5 0v6.5L4.5 18a2 2 0 001.7 3h11.6a2 2 0 001.7-3L14 9.5V3M8 15h8"/>
                    </svg>
                </div>
                <div>
                    <div class="text-base font-extrabold tracking-tight text-heading">SIPINJAM-LAB</div>
                    <div class="text-[11px] text-muted hidden sm:block">Sistem Peminjaman Alat Laboratorium</div>
                </div>
            </a>

            <!-- Desktop Nav -->
            <nav class="landing-nav-links hidden md:flex items-center gap-1" aria-label="Navigasi utama">
                <a href="{{ route('alat.beranda') }}" class="landing-nav-link {{ request()->routeIs('alat.beranda') ? 'is-active' : '' }}">Beranda</a>
                <a href="{{ route('alat.hasil') }}" class="landing-nav-link {{ request()->routeIs('alat.hasil') ? 'is-active' : '' }}">Daftar Alat</a>
                <a href="#prosedur" class="landing-nav-link">Prosedur</a>
                <a href="#tentang" class="landing-nav-link">Tentang Kami</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <button type="button"
                        class="theme-toggle"
                        id="themeToggle"
                        data-theme-toggle
                        aria-label="Aktifkan Dark Mode"
                        aria-pressed="false"
                        title="Ganti Light/Dark Mode">
                    <svg data-theme-icon="light" width="19" height="19" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="4"/>
                        <path stroke-linecap="round" stroke-width="1.8"
                              d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3l1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3l1.42-1.42"/>
                    </svg>
                    <svg data-theme-icon="dark" width="19" height="19" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M20.5 15.5A8.5 8.5 0 018.5 3.5 8.5 8.5 0 1020.5 15.5z"/>
                    </svg>
                </button>

                <div class="hidden md:flex items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-sm">Daftar</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Keluar</button>
                        </form>
                    @endguest
                </div>

                <!-- Mobile Menu Toggle -->
                <button type="button"
                        class="landing-menu-toggle md:hidden"
                        id="landingMenuToggle"
                        data-landing-menu-toggle
                        aria-label="Buka menu navigasi"
                        aria-controls="landingMobileMenu"
                        aria-expanded="false">
                    <svg width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="landingMobileMenu" class="landing-mobile-menu hidden md:hidden">
            <nav class="flex flex-col gap-1 py-2" aria-label="Navigasi mobile">
                <a href="{{ route('alat.beranda') }}" class="landing-nav-link">Beranda</a>
                <a href="{{ route('alat.hasil') }}" class="landing-nav-link">Daftar Alat</a>
                <a href="#prosedur" class="landing-nav-link">Prosedur</a>
                <a href="#tentang" class="landing-nav-link">Tentang Kami</a>
            </nav>
            <div class="pt-3 mt-2 border-t divider-subtle flex flex-wrap gap-2">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm flex-1">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-sm flex-1">Daftar</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm flex-1">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 flex-1">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-full">Keluar</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1">
        <div class="landing-container py-6 sm:py-8 space-y-10">
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
        <div class="landing-container py-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-sm text-muted">
            <div>
                <div class="font-extrabold text-heading">SIPINJAM-LAB</div>
                <p class="text-xs mt-1">Sistem Peminjaman Alat Laboratorium &bull; &copy; {{ now()->year }}</p>
            </div>
            <nav class="flex flex-wrap items-center gap-4 text-xs font-semibold" aria-label="Tautan footer">
                <a href="{{ route('alat.beranda') }}" class="hover:text-accent">Beranda</a>
                <a href="{{ route('alat.hasil') }}" class="hover:text-accent">Daftar Alat</a>
                <a href="{{ route('login') }}" class="hover:text-accent">Masuk</a>
                <a href="{{ route('register') }}" class="hover:text-accent">Daftar</a>
            </nav>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
