<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Anggota')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(30),
                        Select::make('role')
                            ->label('Peran')
                            ->options([
                                'user' => 'Anggota',
                                'admin' => 'Admin',
                            ])
                            ->default('user')
                            ->required(),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->columnSpanFull(),
                    ]),
                Section::make('Verifikasi & Reputasi')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_verified')
                            ->label('Terverifikasi (Cek Identitas Fisik)'),
                        Toggle::make('is_suspended')
                            ->label('Tangguhkan Akun (Blokir Peminjaman)'),
                        Textarea::make('notes')
                            ->label('Catatan Internal Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
