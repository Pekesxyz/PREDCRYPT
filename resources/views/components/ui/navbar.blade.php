@props(['active' => ''])

<nav id="navbar" class="navbar fixed top-0 left-0 right-0 z-50 px-4 sm:px-6 lg:px-8 py-4" x-data="{ mobileOpen: false, userMenu: false }">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/logo.svg') }}" alt="PREDCRYPT Logo" class="w-8 h-8 group-hover:scale-110 transition-transform">
            <span class="text-xl font-bold text-text-primary hidden sm:inline">PREDCRYPT</span>
        </a>

        {{-- Desktop Nav Links --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="nav-link text-sm font-medium {{ $active === 'home' ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('market') }}" class="nav-link text-sm font-medium {{ $active === 'market' ? 'active' : '' }}">Market</a>
            <a href="{{ route('prediction') }}" class="nav-link text-sm font-medium {{ $active === 'prediction' ? 'active' : '' }}">Prediksi</a>
            <a href="{{ route('about') }}" class="nav-link text-sm font-medium {{ $active === 'about' ? 'active' : '' }}">Tentang</a>
        </div>

        {{-- Right Section --}}
        <div class="flex items-center gap-4">
            {{-- Theme Toggle --}}
            <button onclick="toggleTheme()" class="theme-toggle" title="Ganti Tema" id="theme-toggle-btn">
                <span class="moon-icon-wrapper">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </span>
                <span class="sun-icon-wrapper">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                </span>
            </button>

            {{-- Auth Buttons / User Menu --}}
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 glass-card-static px-3 py-2 rounded-xl cursor-pointer hover:border-border-hover transition-all">
                        <div class="w-7 h-7 rounded-full bg-accent flex items-center justify-center text-xs font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-text-secondary hidden lg:inline">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-text-muted transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-bg-secondary shadow-2xl rounded-xl py-2 border border-border z-60">
                        <a href="{{ route('history') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-text-secondary hover:text-accent hover:bg-bg-tertiary transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Riwayat
                        </a>
                        <hr class="my-2 border-border">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-2 text-sm text-danger hover:bg-bg-tertiary transition-all w-full text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="hidden md:inline-flex gradient-btn text-sm px-6 py-2">
                    <span>Masuk</span>
                </a>
            @endauth

            {{-- Mobile Hamburger --}}
            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-bg-tertiary transition-colors">
                <svg class="w-6 h-6 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileOpen = false" class="mobile-menu-overlay md:hidden"></div>

    <div class="mobile-menu md:hidden p-6" :class="{ 'open': mobileOpen }">
        <div class="flex flex-col gap-4 mt-8">
            <a href="{{ route('home') }}" class="nav-link text-base font-medium py-2 {{ $active === 'home' ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('market') }}" class="nav-link text-base font-medium py-2 {{ $active === 'market' ? 'active' : '' }}">Market</a>
            <a href="{{ route('prediction') }}" class="nav-link text-base font-medium py-2 {{ $active === 'prediction' ? 'active' : '' }}">Prediksi</a>
            <a href="{{ route('about') }}" class="nav-link text-base font-medium py-2 {{ $active === 'about' ? 'active' : '' }}">Tentang</a>

            @auth
                <hr class="border-border">
                <a href="{{ route('history') }}" class="nav-link text-base font-medium py-2">Riwayat</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-base font-medium py-2 text-danger w-full text-left">Keluar</button>
                </form>
            @else
                <hr class="border-border">
                <a href="{{ route('login') }}" class="gradient-btn text-center text-sm"><span>Masuk</span></a>
            @endauth
        </div>
    </div>
</nav>

