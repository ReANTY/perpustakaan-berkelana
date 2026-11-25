<x-main-layout title="Kabar & Lapak">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-espresso">Kabar &amp; Lapak</h1>
            <p class="mt-2 text-sm sm:text-base text-muted">
                Jadwal Lapak Baca Buku Gratis di Alun-Alun Pataraksa, laporan kegiatan, dan sorotan diskusi Threads pilihan.
            </p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2 mb-8">
            @php
                $tabs = [
                    'all' => 'Semua',
                    'upcoming_lapak' => 'Jadwal Lapak',
                    'event_report' => 'Laporan Kegiatan',
                    'threads_highlight' => 'Sorotan Threads',
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <a
                    href="{{ route('activities.index', $key === 'all' ? [] : ['type' => $key]) }}"
                    class="px-4 py-2 rounded-full text-xs sm:text-sm font-semibold transition {{ $type === $key ? 'bg-forest text-white shadow-sm' : 'bg-white border border-warm-border text-muted hover:text-espresso' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if($activities->isEmpty())
            <div class="bg-white border border-warm-border rounded-2xl p-12 text-center">
                <p class="font-serif text-xl font-bold text-espresso">Belum ada kabar untuk kategori ini</p>
                <p class="mt-2 text-sm text-muted">Pantau terus informasi terbaru dari Lapak Baca Alun-Alun Pataraksa.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($activities as $activity)
                    @if($activity->type === 'threads_highlight' && $activity->threads_url)
                        <div class="flex flex-col justify-between">
                            <x-threads-embed :url="$activity->threads_url" />
                        </div>
                    @else
                        <article class="bg-white border border-warm-border rounded-xl overflow-hidden shadow-sm flex flex-col justify-between">
                            @if(!empty($activity->gallery_images))
                                <a href="{{ route('activities.show', $activity) }}" class="block aspect-[16/10] bg-cream overflow-hidden">
                                    <img
                                        src="{{ asset('storage/' . $activity->gallery_images[0]) }}"
                                        alt="{{ $activity->title }}"
                                        class="w-full h-full object-cover hover:scale-105 transition duration-300"
                                    >
                                </a>
                            @endif

                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2 text-xs mb-2">
                                        <span class="px-2.5 py-0.5 rounded-full font-semibold {{ $activity->type === 'upcoming_lapak' ? 'bg-terracotta/10 text-terracotta-hover' : 'bg-forest/10 text-forest' }}">
                                            {{ $activity->type_label }}
                                        </span>
                                        @if($activity->event_date)
                                            <span class="text-muted">{{ $activity->event_date->translatedFormat('d M Y') }}</span>
                                        @endif
                                    </div>
                                    <h2 class="font-serif text-xl font-bold text-espresso leading-snug">
                                        <a href="{{ route('activities.show', $activity) }}" class="hover:text-forest transition">
                                            {{ $activity->title }}
                                        </a>
                                    </h2>
                                    @if($activity->content)
                                        <p class="mt-2 text-sm text-muted line-clamp-3 leading-relaxed">
                                            {{ $activity->content }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 border-t border-warm-border flex items-center justify-between text-xs">
                                    <span class="text-muted truncate max-w-[65%]">{{ $activity->location }}</span>
                                    <a href="{{ route('activities.show', $activity) }}" class="font-semibold text-forest hover:text-forest-hover shrink-0">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>

            <div class="mt-8">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</x-main-layout>
