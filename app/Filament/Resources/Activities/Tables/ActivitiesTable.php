<?php

namespace App\Filament\Resources\Activities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'upcoming_lapak' => 'Jadwal Lapak',
                        'event_report' => 'Laporan Kegiatan',
                        'threads_highlight' => 'Sorotan Threads',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'upcoming_lapak' => 'warning',
                        'event_report' => 'success',
                        'threads_highlight' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                IconColumn::make('is_pinned_banner')
                    ->label('Pinned')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'upcoming_lapak' => 'Jadwal Lapak',
                        'event_report' => 'Laporan Kegiatan',
                        'threads_highlight' => 'Sorotan Threads',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
