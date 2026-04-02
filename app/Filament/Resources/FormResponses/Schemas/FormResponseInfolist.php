<?php

namespace App\Filament\Resources\FormResponses\Schemas;


use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class FormResponseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('submitted_at')->label('Tanggal Submit')->dateTime('d/m/Y H:i'),
                        TextEntry::make('cagar_budaya')->label('Cagar Budaya'),
                        TextEntry::make('nama_petugas')->label('Nama Petugas'),
                        TextEntry::make('kegiatan')->label('Kegiatan'),
                        TextEntry::make('dokumentasi_kegiatan')->label('Dokumentasi Kegiatan'),
                    ]),
                Section::make('Data Pengunjung')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('jumlah_pengunjung')->label('Total Pengunjung')->numeric(),
                        TextEntry::make('wisatawan_nusantara')->label('Wisatawan Nusantara')->numeric(),
                        TextEntry::make('wisatawan_mancanegara')->label('Wisatawan Mancanegara')->numeric(),
                        TextEntry::make('pelajar_mahasiswa')->label('Pelajar/Mahasiswa')->numeric(),
                        TextEntry::make('tamu_dinas')->label('Tamu Dinas')->numeric(),
                        TextEntry::make('dokumentasi_kunjungan')->label('Dokumentasi Kunjungan'),
                    ]),
                Section::make('Laporan Kerusakan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('temuan_kerusakan')->label('Temuan Kerusakan')
                            ->badge()
                            ->color(fn($state) => match($state) {
                                'Ada' => 'danger',
                                'Tidak Ada' => 'success',
                                default => 'gray',
                            }),
                        TextEntry::make('deskripsi_kerusakan')->label('Deskripsi Kerusakan'),
                        TextEntry::make('dokumentasi_kerusakan')->label('Dokumentasi Kerusakan'),
                    ]),
                Section::make('Kondisi Keamanan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('kondisi_keamanan_situs')->label('Kondisi Keamanan')
                            ->badge()
                            ->color(fn($state) => match($state) {
                                'Aman dan terkendali' => 'success',
                                'Waspada' => 'warning',
                                'Bahaya' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('catatan_kondisi_keamanan')->label('Catatan Keamanan'),
                    ]),
            ]);
    }
}