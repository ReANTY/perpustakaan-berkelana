<div>
    <!-- Filter & Search Bar -->
    <div class="bg-white border border-warm-border rounded-2xl p-4 sm:p-6 shadow-sm mb-8 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <!-- Search Input -->
            <div class="md:col-span-6">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari judul, penulis, atau kode buku (misal: PB-001)..."
                    class="w-full rounded-xl border border-warm-border bg-cream/50 px-4 py-2.5 text-sm text-espresso placeholder:text-muted focus:border-forest focus:ring-forest"
                >
            </div>

            <!-- Sort -->
            <div class="md:col-span-3">
                <select
                    wire:model.live="sortBy"
                    class="w-full rounded-xl border border-warm-border bg-cream/50 px-3 py-2.5 text-sm text-espresso focus:border-forest focus:ring-forest"
                >
                    <option value="latest">Urutkan: Terbaru</option>
                    <option value="most_requested">Paling Banyak Diminta ke Lapak</option>
                </select>
            </div>

            <!-- Only Available Toggle -->
            <div class="md:col-span-3 flex items-center md:justify-end">
                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                    <input
                        type="checkbox"
                        wire:model.live="onlyAvailable"
                        class="rounded border-warm-border text-forest focus:ring-forest"
                    >
                    <span class="text-sm font-medium text-espresso">Hanya yang Tersedia</span>
                </label>
            </div>
        </div>

        <!-- Category Pills -->
        <div class="flex flex-wrap gap-2 pt-2 border-t border-warm-border">
            <button
                type="button"
                wire:click="setCategory('')"
                class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition {{ $category === '' ? 'bg-forest text-white' : 'bg-cream text-muted hover:text-espresso border border-warm-border' }}"
            >
                Semua Kategori
            </button>
            @foreach($categories as $cat)
                <button
                    type="button"
                    wire:click="setCategory('{{ $cat }}')"
                    class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition {{ $category === $cat ? 'bg-forest text-white' : 'bg-cream text-muted hover:text-espresso border border-warm-border' }}"
                >
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Results Grid -->
    @if($books->isEmpty())
        <div class="bg-white border border-warm-border rounded-2xl p-12 text-center">
            <p class="font-serif text-xl font-bold text-espresso">Buku tidak ditemukan</p>
            <p class="mt-2 text-sm text-muted">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" :livewire-action="true" wire:key="book-{{ $book->id }}" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @endif
</div>
