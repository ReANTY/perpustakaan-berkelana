<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'admin' ? 'warning' : 'gray'),
                ToggleColumn::make('is_verified')
                    ->label('Terverifikasi'),
                ToggleColumn::make('is_suspended')
                    ->label('Ditangguhkan'),
                TextColumn::make('loans_count')
                    ->counts('loans')
                    ->label('Total Pinjam'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'user' => 'Anggota',
                        'admin' => 'Admin',
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
