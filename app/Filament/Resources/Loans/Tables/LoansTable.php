<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Models\Loan;
use App\Services\LoanFineCalculator;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('loan_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('book.title')
                    ->label('Buku')
                    ->searchable()
                    ->limit(28),
                TextColumn::make('collateral_type')
                    ->label('Jaminan')
                    ->badge(),
                TextColumn::make('pickup_date')
                    ->label('Tgl Ambil')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending_pickup' => 'Menunggu Konfirmasi',
                        'active' => 'Sedang Dipinjam',
                        'overdue' => 'Terlambat',
                        'returned' => 'Dikembalikan',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending_pickup' => 'warning',
                        'active' => 'success',
                        'overdue' => 'danger',
                        'returned' => 'gray',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('fine_amount')
                    ->label('Denda')
                    ->formatStateUsing(fn (int $state): string => $state > 0 ? 'Rp ' . number_format($state, 0, ',', '.') : '-')
                    ->color(fn (Loan $record): string => $record->fine_amount > 0 && ! $record->is_fine_paid ? 'danger' : 'gray')
                    ->sortable(),
                IconColumn::make('is_fine_paid')
                    ->label('Lunas')
                    ->boolean(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('confirm_borrowed')
                    ->label('Konfirmasi Dipinjam')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Loan $record): bool => $record->status === 'pending_pickup')
                    ->action(function (Loan $record): void {
                        $now = now();
                        $record->update([
                            'status' => 'active',
                            'borrowed_at' => $now,
                            'due_date' => $now->copy()->addDays($record->duration_days ?: 7)->toDateString(),
                        ]);
                        $record->book?->update(['status' => 'borrowed']);

                        Notification::make()
                            ->title('Peminjaman dikonfirmasi')
                            ->body("Buku {$record->book?->title} resmi dipinjam selama {$record->duration_days} hari.")
                            ->success()
                            ->send();
                    }),
                Action::make('confirm_returned')
                    ->label('Konfirmasi Kembali')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn (Loan $record): bool => in_array($record->status, ['active', 'overdue']))
                    ->action(function (Loan $record): void {
                        LoanFineCalculator::syncAll();
                        $record->refresh();
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                        ]);
                        $record->book?->update(['status' => 'available']);

                        Notification::make()
                            ->title('Buku dikembalikan')
                            ->body("Status buku {$record->book?->title} kembali Tersedia.")
                            ->success()
                            ->send();
                    }),
                Action::make('mark_fine_paid')
                    ->label('Tandai Denda Lunas')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Loan $record): bool => $record->fine_amount > 0 && ! $record->is_fine_paid)
                    ->action(function (Loan $record): void {
                        $record->update(['is_fine_paid' => true]);
                        Notification::make()
                            ->title('Denda ditandai lunas')
                            ->success()
                            ->send();
                    }),
                Action::make('cancel_loan')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Loan $record): bool => $record->status === 'pending_pickup')
                    ->action(function (Loan $record): void {
                        $record->update(['status' => 'cancelled']);
                        $record->book?->update(['status' => 'available']);
                        Notification::make()
                            ->title('Booking dibatalkan')
                            ->warning()
                            ->send();
                    }),
                Action::make('remind_wa')
                    ->label('Ingatkan via WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn (Loan $record): string => $record->borrower_whatsapp_reminder_url)
                    ->openUrlInNewTab()
                    ->visible(fn (Loan $record): bool => in_array($record->status, ['pending_pickup', 'active', 'overdue'])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
