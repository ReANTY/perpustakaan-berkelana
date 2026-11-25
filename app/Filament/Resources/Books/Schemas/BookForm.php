<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Buku')
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode Buku')
                            ->placeholder('PB-001')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),
                        TextInput::make('title')
                            ->label('Judul Buku')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('author')
                            ->label('Penulis')
                            ->required()
                            ->maxLength(255),
                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'Fiksi' => 'Fiksi',
                                'Sastra' => 'Sastra',
                                'Sejarah' => 'Sejarah',
                                'Filsafat' => 'Filsafat',
                                'Pengembangan Diri' => 'Pengembangan Diri',
                                'Anak & Remaja' => 'Anak & Remaja',
                                'Sosial & Budaya' => 'Sosial & Budaya',
                            ])
                            ->searchable()
                            ->required(),
                        TextInput::make('publisher')
                            ->label('Penerbit')
                            ->maxLength(255),
                        TextInput::make('publication_year')
                            ->label('Tahun Terbit')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue((int) date('Y') + 1),
                        Select::make('status')
                            ->label('Status Ketersediaan')
                            ->options([
                                'available' => 'Tersedia',
                                'booked' => 'Dibooking',
                                'borrowed' => 'Dipinjam',
                                'unavailable' => 'Tidak Tersedia',
                            ])
                            ->default('available')
                            ->required(),
                        TextInput::make('lapak_request_count')
                            ->label('Jumlah Permintaan ke Lapak')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Toggle::make('is_featured')
                            ->label('Rekomendasi Admin')
                            ->default(false)
                            ->columnSpanFull(),
                        Textarea::make('synopsis')
                            ->label('Sinopsis')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Sampul Buku')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Upload Sampul Buku')
                            ->image()
                            ->disk('public')
                            ->directory('covers')
                            ->imageEditor(),
                    ]),
            ]);
    }
}
