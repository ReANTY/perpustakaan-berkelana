<x-main-layout>
    @isset($header)
        <header class="bg-white border-b border-warm-border">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <div class="py-8">
        {{ $slot }}
    </div>
</x-main-layout>
