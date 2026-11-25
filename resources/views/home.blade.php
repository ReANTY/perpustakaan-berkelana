<x-main-layout>
    <!-- Hero Section -->
    <section class="border-b border-warm-border bg-gradient-to-b from-white to-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest/10 text-forest text-xs font-semibold tracking-wide uppercase">
                    Ruang Baca Komunitas &bull; Kabupaten Cirebon
                </span>
                <h1 class="mt-4 font-serif text-3xl sm:text-5xl font-bold text-espresso leading-tight">
                    Membaca Bebas di Ruang Terbuka, Merawat Nalar Bersama.
                </h1>
                <p class="mt-4 text-base sm:text-lg text-muted leading-relaxed">
                    Selamat datang di <strong>Perpustakaan Berkelana</strong>. Jelajahi koleksi buku kami, minta buku favoritmu untuk dibawa ke <em>Lapak Baca Buku Gratis</em>, atau pinjam langsung secara daring.
                </p>

                <!-- Quick Search Bar -->
                <form action="{{ route('books.index') }}" method="GET" class="mt-8 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input
                            type="text"
                            name="q"
                            placeholder="Cari judul buku, penulis, atau kode buku..."
                            class="w-full rounded-xl border border-warm-border bg-white px-4 py-3.5 text-sm text-espresso placeholder:text-muted focus:border-forest focus:ring-forest shadow-sm"
                        >
                    </div>
                    <button type="submit" class="px-6 py-3.5 rounded-xl bg-forest hover:bg-forest-hover text-white font-semibold text-sm shadow-sm transition">
                        Cari di Katalog
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Pinned Jadwal Lapak Berikutnya Banner -->
    @if($pinnedLapak)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
            <div class="bg-white border-2 border-terracotta/50 rounded-2xl p-6 sm:p-8 shadow-md relative overflow-hidden">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-terracotta text-white text-xs font-bold uppercase tracking-wider">
                            Jadwal Lapak Berikutnya
                        </div>
                        <h2 class="font-serif text-2xl font-bold text-espresso">
                            {{ $pinnedLapak->title }}
                        </h2>
                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-muted pt-1">
                            @if($pinnedLapak->event_date)
                                <span class="inline-flex items-center gap-1.5 font-medium text-espresso">
                                    &#128197; {{ $pinnedLapak->event_date->translatedFormat('l, d F Y • H:i') }} WIB
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 font-medium text-espresso">
                                &#128205; {{ $pinnedLapak->location }}
                            </span>
                        </div>
                        @if($pinnedLapak->content)
                            <p class="text-sm text-muted leading-relaxed max-w-2xl pt-1">
                                {{ Str::limit($pinnedLapak->content, 200) }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-3 shrink-0">
                        <a href="{{ route('activities.show', $pinnedLapak) }}" class="px-5 py-2.5 rounded-xl bg-terracotta hover:bg-terracotta-hover text-white text-sm font-semibold transition shadow-sm">
                            Lihat Detail Lapak
                        </a>
                        <a href="{{ route('books.index') }}" class="px-5 py-2.5 rounded-xl border border-warm-border bg-cream hover:bg-white text-espresso text-sm font-semibold transition">
                            Request Buku Dibawa
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Buku Pilihan & Baru Ditambahkan -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-14">
        <div class="flex items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold text-espresso">Buku Pilihan &amp; Koleksi Terbaru</h2>
                <p class="mt-1 text-sm text-muted">Klik buku untuk melihat sinopsis, meminjam, atau meminta dibawa ke lapak.</p>
            </div>
            <a href="{{ route('books.index') }}" class="text-sm font-semibold text-forest hover:text-forest-hover shrink-0">
                Lihat Semua Katalog &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($featuredBooks as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
    </section>

    <!-- Jurnal Lapak & Sorotan Threads -->
    @if($recentActivities->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
            <div class="flex items-end justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-espresso">Jurnal Lapak &amp; Sorotan Threads</h2>
                    <p class="mt-1 text-sm text-muted">Catatan kegiatan dari Alun-Alun Pataraksa dan obrolan literasi pilihan.</p>
                </div>
                <a href="{{ route('activities.index') }}" class="text-sm font-semibold text-forest hover:text-forest-hover shrink-0">
                    Semua Kabar &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($recentActivities as $activity)
                    @if($activity->type === 'threads_highlight' && $activity->threads_url)
                        <div class="space-y-2">
                            <x-threads-embed :url="$activity->threads_url" />
                        </div>
                    @else
                        <article class="bg-white border border-warm-border rounded-xl p-6 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 text-xs text-muted mb-2">
                                    <span class="px-2.5 py-0.5 rounded-full bg-forest/10 text-forest font-semibold">
                                        {{ $activity->type_label }}
                                    </span>
                                    @if($activity->event_date)
                                        <span>{{ $activity->event_date->translatedFormat('d M Y') }}</span>
                                    @endif
                                </div>
                                <h3 class="font-serif text-xl font-bold text-espresso">
                                    <a href="{{ route('activities.show', $activity) }}" class="hover:text-forest transition">
                                        {{ $activity->title }}
                                    </a>
                                </h3>
                                @if($activity->content)
                                    <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-3">
                                        {{ $activity->content }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-warm-border flex items-center justify-between text-xs">
                                <span class="text-muted">{{ $activity->location }}</span>
                                <a href="{{ route('activities.show', $activity) }}" class="font-semibold text-forest hover:text-forest-hover">
                                    Baca Selengkapnya &rarr;
                                </a>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>
        </section>
    @endif
</x-main-layout>
