<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Loan;
use App\Services\LoanFineCalculator;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LibraryStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        LoanFineCalculator::syncAll();

        $totalBooks = Book::count();
        $borrowedBooks = Book::where('status', 'borrowed')->count();
        $pendingLoans = Loan::where('status', 'pending_pickup')->count();
        $unpaidFines = Loan::where('status', 'overdue')
            ->where('is_fine_paid', false)
            ->sum('fine_amount');

        return [
            Stat::make('Total Buku', $totalBooks)
                ->description('Koleksi Perpustakaan Berkelana')
                ->icon('heroicon-o-book-open')
                ->color('primary'),
            Stat::make('Buku Sedang Dipinjam', $borrowedBooks)
                ->description('Aktif di tangan anggota')
                ->icon('heroicon-o-arrow-right-circle')
                ->color('success'),
            Stat::make('Permintaan Booking Baru', $pendingLoans)
                ->description('Menunggu konfirmasi serah-terima')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Total Denda Terlambat', 'Rp ' . number_format($unpaidFines, 0, ',', '.'))
                ->description('Denda keterlambatan belum lunas')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
