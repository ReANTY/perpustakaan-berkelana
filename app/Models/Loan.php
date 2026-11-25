<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    public const DAILY_FINE = 2000;

    protected $fillable = [
        'loan_code',
        'user_id',
        'book_id',
        'duration_days',
        'pickup_date',
        'collateral_type',
        'status',
        'borrowed_at',
        'due_date',
        'returned_at',
        'fine_amount',
        'is_fine_paid',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'pickup_date' => 'date',
            'borrowed_at' => 'datetime',
            'due_date' => 'date',
            'returned_at' => 'datetime',
            'fine_amount' => 'integer',
            'is_fine_paid' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public static function generateLoanCode(): string
    {
        $prefix = 'LN-' . now()->format('Ymd') . '-';
        $lastLoan = static::where('loan_code', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastLoan
            ? ((int) substr($lastLoan->loan_code, -3)) + 1
            : 1;

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getDaysLateAttribute(): int
    {
        if (! $this->due_date || in_array($this->status, ['pending_pickup', 'cancelled'])) {
            return 0;
        }

        $compareDate = $this->returned_at
            ? Carbon::parse($this->returned_at)->startOfDay()
            : now()->startOfDay();

        $due = Carbon::parse($this->due_date)->startOfDay();

        return $due->lt($compareDate) ? (int) $due->diffInDays($compareDate) : 0;
    }

    public function getRemainingDaysAttribute(): ?int
    {
        if (! $this->due_date || $this->status !== 'active') {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays(Carbon::parse($this->due_date)->startOfDay(), false);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_pickup' => 'Menunggu Konfirmasi',
            'active' => 'Sedang Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getFormattedFineAttribute(): string
    {
        return 'Rp ' . number_format($this->fine_amount, 0, ',', '.');
    }

    public function getAdminWhatsappBookingUrlAttribute(): string
    {
        $userName = $this->user?->name ?? 'Anggota';
        $bookTitle = $this->book?->title ?? 'Buku';
        $bookCode = $this->book?->code ?? '-';
        $pickupFormatted = $this->pickup_date ? $this->pickup_date->translatedFormat('d F Y') : '-';

        $message = "Halo Admin Perpustakaan Berkelana! Saya *{$userName}* ingin konfirmasi peminjaman buku *{$bookTitle}* (Kode: *{$bookCode}*). Rencana ambil tanggal *{$pickupFormatted}* dengan jaminan *{$this->collateral_type}*. Kode Booking: *{$this->loan_code}*.";

        return 'https://wa.me/6285726029832?text=' . urlencode($message);
    }

    public function getBorrowerWhatsappReminderUrlAttribute(): string
    {
        $phone = $this->user?->phone ?? '';
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        $userName = $this->user?->name ?? 'Kak';
        $bookTitle = $this->book?->title ?? 'Buku';
        $due = $this->due_date ? $this->due_date->translatedFormat('d F Y') : '-';
        $fineText = $this->fine_amount > 0 ? " dengan denda keterlambatan sementara {$this->formatted_fine}" : '';

        $message = "Halo Kak {$userName}, kami dari Perpustakaan Berkelana ingin mengingatkan pengembalian buku *{$bookTitle}* (Kode Booking: {$this->loan_code}) yang jatuh tempo pada tanggal {$due}{$fineText}. Silakan kabari kami jika ingin mengembalikan di Lapak Baca Alun-Alun Pataraksa atau janjian langsung. Terima kasih!";

        return $digits
            ? 'https://wa.me/' . $digits . '?text=' . urlencode($message)
            : 'https://wa.me/?text=' . urlencode($message);
    }
}
