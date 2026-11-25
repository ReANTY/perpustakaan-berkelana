<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class OverdueLoansTable extends TableWidget
{
    protected static ?string $heading = 'Peminjaman Terlambat (Perlu Pengingat)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Loan::query()->with(['user', 'book'])->where('status', 'overdue')->orderBy('due_date'))
            ->columns([
                TextColumn::make('loan_code')
                    ->label('Kode')
                    ->badge()
                    ->color('danger'),
                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable(),
                TextColumn::make('book.title')
                    ->label('Buku')
                    ->limit(35),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y'),
                TextColumn::make('fine_amount')
                    ->label('Denda Berjalan')
                    ->formatStateUsing(fn (int $state): string => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->color('danger')
                    ->weight('bold'),
            ])
            ->recordActions([
                Action::make('remind_wa')
                    ->label('Ingatkan via WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn (Loan $record): string => $record->borrower_whatsapp_reminder_url)
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Tidak ada peminjaman terlambat')
            ->emptyStateDescription('Semua anggota mengembalikan buku tepat waktu.');
    }
}
