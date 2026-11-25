@props(['book', 'livewireAction' => false])

@php
    $badgeClass = match ($book->status) {
        'available' => 'bg-forest/10 text-forest border-forest/20',
        'booked' => 'bg-terracotta/10 text-terracotta-hover border-terracotta/20',
        'borrowed' => 'bg-blue-50 text-blue-700 border-blue-200',
        default => 'bg-gray-100 text-gray-600 border-gray-200',
    };
@endphp

<article class="bg-white border border-warm-border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
    <a href="{{ route('books.show', $book) }}" class="block relative group overflow-hidden bg-cream">
        <img
            src="{{ $book->cover_url }}"
            alt="Sampul {{ $book->title }}"
            class="w-full aspect-[2/3] object-cover rounded-t-lg group-hover:scale-[1.02] transition duration-300"
            loading="lazy"
        >
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border backdrop-blur-sm {{ $badgeClass }}">
                {{ $book->status_label }}
            </span>
            @if($book->is_featured)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-terracotta text-white shadow-sm">
                    Rekomendasi Admin
                </span>
            @endif
        </div>
    </a>

    <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between gap-2 text-xs text-muted mb-1">
                <span class="font-medium">{{ $book->category }}</span>
                <span class="font-mono">{{ $book->code }}</span>
            </div>
            <h3 class="font-serif text-base font-bold text-espresso leading-snug line-clamp-2">
                <a href="{{ route('books.show', $book) }}" class="hover:text-forest transition">
                    {{ $book->title }}
                </a>
            </h3>
            <p class="mt-1 text-xs text-muted line-clamp-1">{{ $book->author }}</p>
        </div>

        <div class="mt-4 pt-3 border-t border-warm-border flex items-center justify-between gap-2">
            @if($livewireAction)
                <button
                    type="button"
                    wire:click="requestLapak({{ $book->id }})"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-terracotta-hover hover:text-terracotta transition"
                    title="Minta buku ini dibawa ke Lapak Baca berikutnya"
                >
                    <span>&#128218; Minta ke Lapak</span>
                    <span class="px-1.5 py-0.5 rounded-full bg-terracotta/10 text-terracotta-hover font-bold">
                        {{ $book->lapak_request_count }}
                    </span>
                </button>
            @else
                <form method="POST" action="{{ route('books.request-lapak', $book) }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-terracotta-hover hover:text-terracotta transition"
                        title="Minta buku ini dibawa ke Lapak Baca berikutnya"
                    >
                        <span>&#128218; Minta ke Lapak</span>
                        <span class="px-1.5 py-0.5 rounded-full bg-terracotta/10 text-terracotta-hover font-bold">
                            {{ $book->lapak_request_count }}
                        </span>
                    </button>
                </form>
            @endif

            <a href="{{ route('books.show', $book) }}" class="text-xs font-semibold text-forest hover:text-forest-hover">
                Detail &rarr;
            </a>
        </div>
    </div>
</article>
