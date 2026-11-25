<x-main-layout :title="$book->title">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-muted hover:text-espresso mb-6">
            &larr; Kembali ke Katalog Buku
        </a>

        <div class="bg-white border border-warm-border rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Cover -->
                <div class="md:col-span-4">
                    <img
                        src="{{ $book->cover_url }}"
                        alt="Sampul {{ $book->title }}"
                        class="w-full aspect-[2/3] object-cover rounded-lg border border-warm-border shadow-sm"
                    >

                    <!-- Request Lapak Button -->
                    <form method="POST" action="{{ route('books.request-lapak', $book) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl border border-terracotta/40 bg-terracotta/10 hover:bg-terracotta/20 text-terracotta-hover font-semibold text-sm transition flex items-center justify-center gap-2">
                            <span>&#128218; Minta Bawa ke Lapak</span>
                            <span class="px-2 py-0.5 rounded-full bg-white text-xs font-bold">{{ $book->lapak_request_count }}</span>
                        </button>
                    </form>
                </div>

                <!-- Book Info & Borrowing Form -->
                <div class="md:col-span-8 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cream border border-warm-border text-espresso font-mono">
                                {{ $book->code }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-terracotta/10 text-terracotta-hover">
                                {{ $book->category }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $book->isAvailable() ? 'bg-forest/10 text-forest' : 'bg-gray-100 text-gray-600' }}">
                                {{ $book->status_label }}
                            </span>
                            @if($book->is_featured)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-terracotta text-white">
                                    Rekomendasi Admin
                                </span>
                            @endif
                        </div>

                        <h1 class="font-serif text-2xl sm:text-4xl font-bold text-espresso leading-tight">
                            {{ $book->title }}
                        </h1>
                        <p class="mt-2 text-base font-medium text-muted">
                            oleh <span class="text-espresso">{{ $book->author }}</span>
                        </p>

                        <dl class="mt-4 grid grid-cols-2 gap-4 py-4 border-y border-warm-border text-sm">
                            <div>
                                <dt class="text-xs text-muted">Penerbit</dt>
                                <dd class="font-medium text-espresso">{{ $book->publisher ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted">Tahun Terbit</dt>
                                <dd class="font-medium text-espresso">{{ $book->publication_year ?: '-' }}</dd>
                            </div>
                        </dl>

                        <div class="mt-4">
                            <h2 class="text-sm font-bold uppercase tracking-wider text-espresso mb-2">Sinopsis</h2>
                            <p class="text-sm text-muted leading-relaxed whitespace-pre-line">
                                {{ $book->synopsis ?: 'Belum ada sinopsis untuk buku ini.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Hybrid Borrowing Box -->
                    <div class="bg-cream border border-warm-border rounded-xl p-5">
                        <h3 class="font-serif text-lg font-bold text-espresso mb-3">Ajukan Peminjaman Buku</h3>

                        @guest
                            <p class="text-sm text-muted mb-4">
                                Untuk meminjam buku dari Perpustakaan Berkelana, silakan masuk terlebih dahulu menggunakan akun anggota Anda.
                            </p>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-forest hover:bg-forest-hover text-white text-sm font-semibold transition shadow-sm">
                                Login untuk Meminjam
                            </a>
                        @else
                            @if(auth()->user()->is_suspended)
                                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                                    <strong>Akun Ditangguhkan:</strong> Akun Anda sedang ditangguhkan sehingga tidak dapat mengajukan peminjaman baru. Silakan hubungi Admin via WhatsApp untuk klarifikasi.
                                </div>
                            @elseif(! $book->isAvailable())
                                <div class="p-4 rounded-xl bg-white border border-warm-border text-muted text-sm">
                                    Buku ini saat ini berstatus <strong>{{ $book->status_label }}</strong> dan belum bisa dipinjam. Anda tetap dapat menekan tombol <em>Minta Bawa ke Lapak</em> di samping.
                                </div>
                            @else
                                <form method="POST" action="{{ route('books.borrow', $book) }}" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-espresso mb-1">Durasi Pinjam</label>
                                            <div class="px-3.5 py-2.5 rounded-xl border border-warm-border bg-white text-sm font-semibold text-forest">
                                                7 Hari (Standar)
                                            </div>
                                        </div>

                                        <div>
                                            <label for="pickup_date" class="block text-xs font-semibold text-espresso mb-1">Rencana Tanggal Pengambilan</label>
                                            <input
                                                type="date"
                                                id="pickup_date"
                                                name="pickup_date"
                                                value="{{ old('pickup_date', $defaultPickupDate) }}"
                                                min="{{ now()->toDateString() }}"
                                                required
                                                class="w-full rounded-xl border border-warm-border bg-white px-3.5 py-2 text-sm text-espresso focus:border-forest focus:ring-forest"
                                            >
                                            @error('pickup_date')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-espresso mb-2">Jaminan Fisik (Ditunjukkan saat pengambilan)</label>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                            @foreach(['KTP', 'KTM', 'SIM', 'Kartu Pelajar'] as $collateral)
                                                <label class="cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        name="collateral_type"
                                                        value="{{ $collateral }}"
                                                        class="peer sr-only"
                                                        {{ old('collateral_type', 'KTP') === $collateral ? 'checked' : '' }}
                                                        required
                                                    >
                                                    <div class="text-center py-2 px-3 rounded-xl border border-warm-border bg-white text-xs font-semibold text-espresso peer-checked:border-forest peer-checked:bg-forest peer-checked:text-white transition">
                                                        {{ $collateral }}
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('collateral_type')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit" class="w-full py-3 px-5 rounded-xl bg-forest hover:bg-forest-hover text-white font-semibold text-sm shadow-sm transition">
                                        Ajukan Pinjam &amp; Konfirmasi via WhatsApp
                                    </button>
                                </form>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
