<x-main-layout title="Pinjaman Saya">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
        <div>
            <h1 class="font-serif text-3xl font-bold text-espresso">Pinjaman Saya</h1>
            <p class="mt-1 text-sm text-muted">Pantau status booking, tenggat pengembalian, dan riwayat bacaanmu di Perpustakaan Berkelana.</p>
        </div>

        @if(auth()->user()->is_suspended)
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <strong>Perhatian:</strong> Akun Anda sedang ditangguhkan sementara. Hubungi Admin via WhatsApp untuk informasi lebih lanjut.
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-warm-border rounded-xl p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-muted">Sedang Dipinjam</p>
                <p class="mt-2 font-serif text-3xl font-bold text-forest">{{ $activeCount }}</p>
            </div>
            <div class="bg-white border border-warm-border rounded-xl p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-muted">Terlambat</p>
                <p class="mt-2 font-serif text-3xl font-bold {{ $overdueCount > 0 ? 'text-red-600' : 'text-espresso' }}">{{ $overdueCount }}</p>
            </div>
            <div class="bg-white border border-warm-border rounded-xl p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-muted">Total Denda Belum Lunas</p>
                <p class="mt-2 font-serif text-3xl font-bold {{ $totalUnpaidFines > 0 ? 'text-red-600' : 'text-espresso' }}">
                    Rp {{ number_format($totalUnpaidFines, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Active & Pending Loans -->
        <section>
            <h2 class="font-serif text-xl font-bold text-espresso mb-4">Peminjaman Aktif &amp; Menunggu Konfirmasi</h2>

            @if($activeLoans->isEmpty())
                <div class="bg-white border border-warm-border rounded-xl p-8 text-center">
                    <p class="text-sm text-muted">Belum ada peminjaman aktif saat ini.</p>
                    <a href="{{ route('books.index') }}" class="mt-4 inline-flex px-5 py-2.5 rounded-xl bg-forest text-white text-sm font-semibold hover:bg-forest-hover transition">
                        Jelajahi Katalog Buku
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($activeLoans as $loan)
                        @php
                            $remainingDays = $loan->remaining_days;
                            $isH1Warning = $loan->status === 'active' && $remainingDays !== null && $remainingDays <= 1 && $remainingDays >= 0;
                        @endphp
                        <div class="bg-white border border-warm-border rounded-xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <img src="{{ $loan->book->cover_url }}" alt="{{ $loan->book->title }}" class="w-14 aspect-[2/3] object-cover rounded-lg border border-warm-border shrink-0">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="font-mono text-xs px-2 py-0.5 rounded bg-cream border border-warm-border text-espresso">
                                            {{ $loan->loan_code }}
                                        </span>

                                        @if($loan->status === 'pending_pickup')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-terracotta/10 text-terracotta-hover">
                                                Menunggu Konfirmasi
                                            </span>
                                        @elseif($loan->status === 'overdue')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                Terlambat {{ $loan->days_late }} Hari
                                            </span>
                                        @elseif($isH1Warning)
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-terracotta text-white">
                                                {{ $remainingDays === 0 ? 'Jatuh Tempo Hari Ini!' : 'Besok jatuh tempo pengembalian!' }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-forest/10 text-forest">
                                                Sisa {{ $remainingDays }} hari
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="font-serif text-lg font-bold text-espresso">
                                        <a href="{{ route('books.show', $loan->book) }}" class="hover:text-forest transition">
                                            {{ $loan->book->title }}
                                        </a>
                                    </h3>

                                    <div class="mt-1 text-xs text-muted space-x-3">
                                        <span>Jaminan: <strong>{{ $loan->collateral_type }}</strong></span>
                                        @if($loan->status === 'pending_pickup')
                                            <span>Rencana Ambil: <strong>{{ $loan->pickup_date->translatedFormat('d M Y') }}</strong></span>
                                        @elseif($loan->due_date)
                                            <span>Jatuh Tempo: <strong>{{ $loan->due_date->translatedFormat('d M Y') }}</strong></span>
                                        @endif
                                    </div>

                                    @if($loan->status === 'overdue')
                                        <p class="mt-2 text-xs font-bold text-red-600">
                                            Denda berjalan: {{ $loan->formatted_fine }}
                                            @if($loan->is_fine_paid) (Lunas) @endif
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 shrink-0">
                                @if($loan->status === 'pending_pickup')
                                    <a href="{{ $loan->admin_whatsapp_booking_url }}" target="_blank" rel="noopener" class="px-4 py-2 rounded-xl bg-forest hover:bg-forest-hover text-white text-xs font-semibold transition">
                                        Chat WA Admin untuk Konfirmasi &nearr;
                                    </a>
                                @elseif($loan->status === 'overdue')
                                    <a href="https://wa.me/6285726029832?text={{ urlencode("Halo Admin Perpustakaan Berkelana, saya " . auth()->user()->name . " ingin mengembalikan buku {$loan->book->title} (Kode: {$loan->loan_code}).") }}" target="_blank" rel="noopener" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
                                        Koordinasi Pengembalian via WA &nearr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Loan History -->
        <section>
            <h2 class="font-serif text-xl font-bold text-espresso mb-4">Riwayat Peminjaman</h2>
            <div class="bg-white border border-warm-border rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-cream border-b border-warm-border text-xs uppercase text-muted">
                            <tr>
                                <th class="px-5 py-3">Kode</th>
                                <th class="px-5 py-3">Judul Buku</th>
                                <th class="px-5 py-3">Tanggal Kembali</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-warm-border">
                            @forelse($historyLoans as $loan)
                                <tr>
                                    <td class="px-5 py-3.5 font-mono text-xs">{{ $loan->loan_code }}</td>
                                    <td class="px-5 py-3.5 font-medium text-espresso">{{ $loan->book->title }}</td>
                                    <td class="px-5 py-3.5 text-muted">
                                        {{ $loan->returned_at ? $loan->returned_at->translatedFormat('d M Y') : '-' }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ $loan->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-muted">
                                        {{ $loan->fine_amount > 0 ? $loan->formatted_fine . ($loan->is_fine_paid ? ' (Lunas)' : ' (Belum Lunas)') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-muted text-sm">Belum ada riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-main-layout>
