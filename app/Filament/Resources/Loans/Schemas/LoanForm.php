<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\Loan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Peminjaman')
                    ->columns(2)
                    ->schema([
                        TextInput::make('loan_code')
                            ->label('Kode Booking')
                            ->default(fn () => Loan::generateLoanCode())
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('user_id')
                            ->label('Peminjam')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('book_id')
                            ->label('Buku')
                            ->relationship('book', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('collateral_type')
                            ->label('Jaminan Fisik')
                            ->options([
                                'KTP' => 'KTP',
                                'KTM' => 'KTM',
                                'SIM' => 'SIM',
                                'Kartu Pelajar' => 'Kartu Pelajar',
                            ])
                            ->required(),
                        DatePicker::make('pickup_date')
                            ->label('Rencana Tanggal Ambil')
                            ->required(),
                        TextInput::make('duration_days')
                            ->label('Durasi (Hari)')
                            ->numeric()
                            ->default(7)
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending_pickup' => 'Menunggu Konfirmasi',
                                'active' => 'Sedang Dipinjam',
                                'overdue' => 'Terlambat',
                                'returned' => 'Dikembalikan',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending_pickup')
                            ->required(),
                    ]),
                Section::make('Waktu & Denda')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('borrowed_at')
                            ->label('Tanggal Diserahkan'),
                        DatePicker::make('due_date')
                            ->label('Jatuh Tempo'),
                        DateTimePicker::make('returned_at')
                            ->label('Tanggal Dikembalikan'),
                        TextInput::make('fine_amount')
                            ->label('Denda (Rp)')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_fine_paid')
                            ->label('Denda Sudah Lunas')
                            ->default(false),
                        Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
