<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}Perpustakaan Berkelana</title>

    <!-- Google Fonts: Lora & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-cream text-espresso font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <header x-data="{ open: false }" class="sticky top-0 z-40 bg-cream/95 backdrop-blur border-b border-warm-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-lg bg-forest text-cream flex items-center justify-center shadow-sm group-hover:bg-forest-hover transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-serif text-lg sm:text-xl font-bold tracking-tight text-espresso leading-tight">Perpustakaan Berkelana</span>
                        <span class="block text-xs text-muted">Ruang Baca &amp; Lapak Literasi Sumber</span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-forest font-semibold' : 'text-muted hover:text-espresso' }} transition">Beranda</a>
                    <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'text-forest font-semibold' : 'text-muted hover:text-espresso' }} transition">Katalog Buku</a>
                    <a href="{{ route('activities.index') }}" class="{{ request()->routeIs('activities.*') ? 'text-forest font-semibold' : 'text-muted hover:text-espresso' }} transition">Kabar &amp; Lapak</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-forest font-semibold' : 'text-muted hover:text-espresso' }} transition">Pinjaman Saya</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="/admin" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-terracotta/10 text-terracotta-hover font-semibold hover:bg-terracotta/20 transition">
                                Panel Admin
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Auth Actions (Desktop) -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ route('profile.edit') }}" class="text-sm text-muted hover:text-espresso font-medium">
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3.5 py-2 text-sm font-medium rounded-lg border border-warm-border bg-white hover:bg-cream text-espresso transition">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-muted hover:text-espresso transition px-2">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold rounded-lg bg-forest text-white hover:bg-forest-hover transition shadow-sm">
                            Daftar Anggota
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" type="button" class="md:hidden p-2 rounded-lg border border-warm-border bg-white text-espresso">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Dropdown -->
            <div x-show="open" x-cloak x-transition class="md:hidden pb-4 pt-2 border-t border-warm-border space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-forest/10 text-forest font-semibold' : 'text-espresso' }}">Beranda</a>
                <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('books.*') ? 'bg-forest/10 text-forest font-semibold' : 'text-espresso' }}">Katalog Buku</a>
                <a href="{{ route('activities.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('activities.*') ? 'bg-forest/10 text-forest font-semibold' : 'text-espresso' }}">Kabar &amp; Lapak</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-forest/10 text-forest font-semibold' : 'text-espresso' }}">Pinjaman Saya</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="/admin" class="block px-3 py-2 rounded-lg text-sm font-semibold text-terracotta-hover bg-terracotta/10">Panel Admin</a>
                    @endif
                    <div class="pt-2 border-t border-warm-border flex items-center justify-between px-3">
                        <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-muted">{{ auth()->user()->name }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-red-600">Keluar</button>
                        </form>
                    </div>
                @else
                    <div class="pt-2 border-t border-warm-border flex gap-2 px-3">
                        <a href="{{ route('login') }}" class="flex-1 text-center py-2 rounded-lg border border-warm-border bg-white text-sm font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="flex-1 text-center py-2 rounded-lg bg-forest text-white text-sm font-semibold">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Toast Notification -->
    <div
        x-data="{
            show: false,
            message: '',
            type: 'success',
            timer: null,
            trigger(detail) {
                this.type = detail.type || 'success';
                this.message = detail.message || '';
                this.show = true;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => this.show = false, 5000);
            }
        }"
        x-init="
            @if(session('success')) trigger({ type: 'success', message: @js(session('success')) }); @endif
            @if(session('info')) trigger({ type: 'info', message: @js(session('info')) }); @endif
            @if(session('error')) trigger({ type: 'error', message: @js(session('error')) }); @endif
        "
        @toast.window="trigger($event.detail)"
        x-show="show"
        x-cloak
        x-transition
        class="fixed bottom-5 right-5 z-50 max-w-sm w-full px-4"
    >
        <div
            :class="{
                'bg-forest text-white border-forest': type === 'success',
                'bg-terracotta text-white border-terracotta': type === 'info',
                'bg-red-600 text-white border-red-700': type === 'error'
            }"
            class="rounded-xl shadow-lg border px-4 py-3.5 flex items-start gap-3 text-sm"
        >
            <div class="flex-1 font-medium leading-relaxed" x-text="message"></div>
            <button @click="show = false" class="text-white/80 hover:text-white font-bold">&times;</button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-16 bg-white border-t border-warm-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-serif text-lg font-bold text-espresso">Perpustakaan Berkelana</h3>
                    <p class="mt-2 text-sm text-muted leading-relaxed">
                        Ruang baca komunitas dan gerakan literasi jalanan di Kabupaten Cirebon. Membaca gratis, meminjam buku dengan mudah, dan merawat kebiasaan membaca bersama.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-espresso">Lapak Baca Buku Gratis</h4>
                    <p class="mt-2 text-sm text-muted leading-relaxed">
                        <strong>Lokasi Rutin:</strong><br>
                        Alun-Alun Pataraksa, Sumber, Kabupaten Cirebon
                    </p>
                    <p class="mt-2 text-sm text-muted">
                        Setiap akhir pekan / sesuai pengumuman jadwal lapak terbaru.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-espresso">Terhubung dengan Kami</h4>
                    <div class="mt-3 space-y-2.5 text-sm">
                        <a href="https://wa.me/6285726029832" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-forest hover:text-forest-hover font-semibold">
                            <span>WhatsApp Admin: 0857-2602-9832</span>
                            <span>&nearr;</span>
                        </a>
                        <br>
                        <a href="https://www.threads.net/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-muted hover:text-espresso font-medium">
                            <span>Ikuti kami di Threads</span>
                            <span>&nearr;</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-warm-border flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-muted">
                <p>&copy; {{ date('Y') }} Perpustakaan Berkelana — Sumber, Kabupaten Cirebon.</p>
                <p>Dibangun dengan semangat literasi terbuka.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
