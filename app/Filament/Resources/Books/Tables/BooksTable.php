<?php

namespace App\Filament\Resources\Books\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('lapak_request_count', 'desc')
            ->columns([
                ImageColumn::make('cover_url')
                    ->label('Sampul')
                    ->height(54)
                    ->width(36),
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(35),
                TextColumn::make('author')
                    ->label('Penulis')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Tersedia',
                        'booked' => 'Dibooking',
                        'borrowed' => 'Dipinjam',
                        'unavailable' => 'Tidak Tersedia',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'booked' => 'warning',
                        'borrowed' => 'info',
                        'unavailable' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_featured')
                    ->label('Pilihan')
                    ->boolean(),
                TextColumn::make('lapak_request_count')
                    ->label('Req. Lapak')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'booked' => 'Dibooking',
                        'borrowed' => 'Dipinjam',
                        'unavailable' => 'Tidak Tersedia',
                    ]),
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Fiksi' => 'Fiksi',
                        'Sastra' => 'Sastra',
                        'Sejarah' => 'Sejarah',
                        'Filsafat' => 'Filsafat',
                        'Pengembangan Diri' => 'Pengembangan Diri',
                        'Anak & Remaja' => 'Anak & Remaja',
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Rekomendasi Admin'),
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
