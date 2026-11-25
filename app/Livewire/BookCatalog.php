<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BookCatalog extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url(as: 'tersedia')]
    public bool $onlyAvailable = false;

    #[Url(as: 'urutkan')]
    public string $sortBy = 'latest';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function updatingOnlyAvailable(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function setCategory(string $category): void
    {
        $this->category = $this->category === $category ? '' : $category;
        $this->resetPage();
    }

    public function requestLapak(int $bookId): void
    {
        $book = Book::find($bookId);
        if (! $book) {
            return;
        }

        $today = now()->toDateString();
        $sessionKey = "lapak_requested_books.{$bookId}";
        $cacheKey = "lapak_req:{$bookId}:" . request()->ip() . ":{$today}";

        if (session()->get($sessionKey) === $today || ! Cache::add($cacheKey, true, now()->endOfDay())) {
            $this->dispatch('toast', type: 'info', message: 'Kamu sudah meminta buku ini hari ini. Terima kasih atas antusiasmenya!');
            return;
        }

        session()->put($sessionKey, $today);
        $book->increment('lapak_request_count');

        $this->dispatch('toast', type: 'success', message: 'Permintaan dicatat! Buku ini akan diprioritaskan untuk dibawa ke Lapak Baca Buku Gratis berikutnya.');
    }

    public function render()
    {
        $categories = Book::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $query = Book::query();

        if (filled($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('author', 'like', $term)
                    ->orWhere('code', 'like', $term);
            });
        }

        if (filled($this->category)) {
            $query->where('category', $this->category);
        }

        if ($this->onlyAvailable) {
            $query->where('status', 'available');
        }

        if ($this->sortBy === 'most_requested') {
            $query->orderByDesc('lapak_request_count')->latest();
        } else {
            $query->latest();
        }

        return view('livewire.book-catalog', [
            'books' => $query->paginate(12),
            'categories' => $categories,
        ]);
    }
}
