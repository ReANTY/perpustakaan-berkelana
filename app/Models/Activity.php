<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'event_date',
        'location',
        'content',
        'threads_url',
        'gallery_images',
        'is_pinned_banner',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'gallery_images' => 'array',
            'is_pinned_banner' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Activity $activity) {
            if (empty($activity->slug)) {
                $baseSlug = Str::slug($activity->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $activity->slug = $slug;
            }
        });
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'upcoming_lapak' => 'Jadwal Lapak',
            'event_report' => 'Laporan Kegiatan',
            'threads_highlight' => 'Sorotan Threads',
            default => ucfirst($this->type),
        };
    }

    public function getNormalizedThreadsUrlAttribute(): ?string
    {
        if (! $this->threads_url) {
            return null;
        }

        return str_replace('threads.com', 'threads.net', $this->threads_url);
    }
}
