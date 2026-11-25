<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'author',
        'publisher',
        'publication_year',
        'category',
        'synopsis',
        'cover_image',
        'status',
        'is_featured',
        'lapak_request_count',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'is_featured' => 'boolean',
            'lapak_request_count' => 'integer',
        ];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function getCoverUrlAttribute(): string
    {
        if (! $this->cover_image) {
            $encodedTitle = urlencode(Str::limit($this->title, 28, ''));
            return "https://placehold.co/400x600/3A5A40/FAF8F5?text={$encodedTitle}";
        }

        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }

        return asset('storage/' . ltrim($this->cover_image, '/'));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'booked' => 'Dibooking',
            'borrowed' => 'Dipinjam',
            'unavailable' => 'Tidak Tersedia',
            default => ucfirst($this->status),
        };
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
