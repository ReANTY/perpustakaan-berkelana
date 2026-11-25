<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use App\Services\LoanFineCalculator;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    public function mount(): void
    {
        parent::mount();
        LoanFineCalculator::syncAll();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'menunggu' => Tab::make('Menunggu Konfirmasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending_pickup')),
            'aktif' => Tab::make('Sedang Dipinjam')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active')),
            'terlambat' => Tab::make('Terlambat')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'overdue')),
            'selesai' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['returned', 'cancelled'])),
        ];
    }
}
