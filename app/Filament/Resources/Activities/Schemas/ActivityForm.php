<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kegiatan / Kabar')
                    ->columns(2)
                    ->schema([
                        Select::make('type')
                            ->label('Jenis Konten')
                            ->options([
                                'upcoming_lapak' => 'Jadwal Lapak (H-1 / Pengumuman)',
                                'event_report' => 'Laporan Kegiatan',
                                'threads_highlight' => 'Sorotan Threads',
                            ])
                            ->required()
                            ->live(),
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, ?string $operation): void {
                                if ($operation === 'create' && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        DateTimePicker::make('event_date')
                            ->label('Tanggal & Waktu Kegiatan'),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->default('Alun-Alun Pataraksa, Sumber, Cirebon')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('content')
                            ->label('Deskripsi / Narasi')
                            ->rows(5)
                            ->columnSpanFull(),
                        TextInput::make('threads_url')
                            ->label('Tautan Postingan Threads')
                            ->placeholder('https://www.threads.net/@perpustakaan.berkelana/post/...')
                            ->url()
                            ->columnSpanFull(),
                        FileUpload::make('gallery_images')
                            ->label('Dokumentasi Foto Kegiatan')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('activities')
                            ->reorderable()
                            ->columnSpanFull(),
                        Toggle::make('is_pinned_banner')
                            ->label('Tampilkan di Banner Utama Beranda (Jadwal Lapak Berikutnya)')
                            ->default(false),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(true),
                    ]),
            ]);
    }
}
