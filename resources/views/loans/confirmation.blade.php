<x-main-layout title="Konfirmasi Peminjaman">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white border border-warm-border rounded-2xl p-6 sm:p-8 shadow-sm text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-forest/10 text-forest flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-espresso">Permintaan Pinjam Dicatat!</h1>
            <p class="mt-2 text-sm text-muted">
                Buku sudah kami kunci sementara untuk Anda. Silakan klik tombol WhatsApp di bawah untuk mengonfirmasi jadwal pengambilan dengan Admin.
            </p>

            <div class="mt-6 bg-cream border border-warm-border rounded-xl p-5 text-left space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-muted">Kode Booking</span>
                    <span class="font-mono font-bold text-espresso">{{ $loan->loan_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Judul Buku</span>
                    <span class="font-semibold text-espresso text-right">{{ $loan->book->title }} ({{ $loan->book->code }})</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Rencana Ambil</span>
                    <span class="font-semibold text-espresso">{{ $loan->pickup_date->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Durasi Pinjam</span>
                    <span class="font-semibold text-espresso">{{ $loan->duration_days }} Hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Jaminan Fisik</span>
                    <span class="font-semibold text-espresso">{{ $loan->collateral_type }}</span>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a
                    href="{{ $loan->admin_whatsapp_booking_url }}"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-forest hover:bg-forest-hover text-white font-semibold text-sm shadow-sm transition"
                >
                    <span>Chat WA Admin untuk Konfirmasi</span>
                    <span>&nearr;</span>
                </a>
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-warm-border bg-white hover:bg-cream text-espresso font-semibold text-sm transition"
                >
                    Lihat Pinjaman Saya
                </a>
            </div>
        </div>
    </div>
</x-main-layout>
