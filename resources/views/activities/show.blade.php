<x-main-layout :title="$activity->title">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ lightbox: null }">
        <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-muted hover:text-espresso mb-6">
            &larr; Kembali ke Kabar &amp; Lapak
        </a>

        <article class="bg-white border border-warm-border rounded-2xl p-6 sm:p-10 shadow-sm">
            <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                <span class="px-3 py-1 rounded-full font-semibold {{ $activity->type === 'upcoming_lapak' ? 'bg-terracotta/10 text-terracotta-hover' : 'bg-forest/10 text-forest' }}">
                    {{ $activity->type_label }}
                </span>
                @if($activity->event_date)
                    <span class="text-muted font-medium">
                        &#128197; {{ $activity->event_date->translatedFormat('l, d F Y • H:i') }} WIB
                    </span>
                @endif
                <span class="text-muted font-medium">
                    &#128205; {{ $activity->location }}
                </span>
            </div>

            <h1 class="font-serif text-2xl sm:text-4xl font-bold text-espresso leading-tight">
                {{ $activity->title }}
            </h1>

            @if($activity->content)
                <div class="mt-6 text-base text-espresso/90 leading-relaxed whitespace-pre-line">
                    {{ $activity->content }}
                </div>
            @endif

            @if($activity->threads_url)
                <div class="mt-8">
                    <x-threads-embed :url="$activity->threads_url" />
                </div>
            @endif

            @if(!empty($activity->gallery_images))
                <div class="mt-8 pt-8 border-t border-warm-border">
                    <h2 class="font-serif text-xl font-bold text-espresso mb-4">Dokumentasi Foto</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($activity->gallery_images as $image)
                            <button
                                type="button"
                                @click="lightbox = '{{ asset('storage/' . $image) }}'"
                                class="aspect-square rounded-xl overflow-hidden border border-warm-border bg-cream group"
                            >
                                <img
                                    src="{{ asset('storage/' . $image) }}"
                                    alt="Dokumentasi {{ $activity->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                >
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </article>

        <!-- Lightbox Modal -->
        <div
            x-show="lightbox"
            x-cloak
            @click="lightbox = null"
            @keydown.escape.window="lightbox = null"
            class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
        >
            <img :src="lightbox" alt="Preview foto" class="max-h-[90vh] max-w-full rounded-xl shadow-2xl">
        </div>
    </div>
</x-main-layout>
