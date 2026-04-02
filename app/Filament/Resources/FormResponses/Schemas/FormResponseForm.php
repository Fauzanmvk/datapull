<?php

namespace App\Filament\Resources\FormResponses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
class FormResponseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('submitted_at')->label('Tanggal Submit')->required(),
                        Select::make('cagar_budaya')
                            ->label('Cagar Budaya')
                            ->options([
                                'Masjid Shiratal Mustaqim' => 'Masjid Shiratal Mustaqim',
                                'Makam La Mohang Daeng Mangkona' => 'Makam La Mohang Daeng Mangkona',
                                'Situs Gunung Selendang' => 'Situs Gunung Selendang',
                                'Situs Kutai Muara Kaman' => 'Situs Kutai Muara Kaman',
                                'Masjid Jami Adji Amir Hasanoeddin' => 'Masjid Jami Adji Amir Hasanoeddin',
                                'Komplek Makam Raja Kutai Kertanegara' => 'Komplek Makam Raja Kutai Kertanegara',
                                'Makam Awang Long Senopati' => 'Makam Awang Long Senopati',
                                'Makam Aji Dilanggar' => 'Makam Aji Dilanggar',
                                'Keraton dan Tiang Prasasti Sambaliung' => 'Keraton dan Tiang Prasasti Sambaliung',
                                'Masjid Kuno Pasir Belengkong' => 'Masjid Kuno Pasir Belengkong',
                                'Museum Pasir Belengkong' => 'Museum Pasir Belengkong',
                                'Makam Raja Pasir Belengkong' => 'Makam Raja Pasir Belengkong',
                                'Lamin Pepas Eheng' => 'Lamin Pepas Eheng',
                                'Lamin Mancong' => 'Lamin Mancong',
                                'Lamin Tolan' => 'Lamin Tolan',
                                'Gua Pindi' => 'Gua Pindi',
                                'Gua Karim' => 'Gua Karim',
                                'Ceruk Tewet Atas' => 'Ceruk Tewet Atas',
                                'Situs Batu Raya' => 'Situs Batu Raya',
                                'Situs Sungai Marang II' => 'Situs Sungai Marang II',
                                'Situs Bekas Keraton Bulungan I' => 'Situs Bekas Keraton Bulungan I',
                                'Walaya Purupun' => 'Walaya Purupun',
                            ])
                            ->required()->searchable(),
                        TextInput::make('nama_petugas')->label('Nama Petugas')->required(),
                        Textarea::make('kegiatan')->label('Kegiatan')->columnSpanFull(),
                        TextInput::make('dokumentasi_kegiatan')->label('Dokumentasi Kegiatan'),
                    ]),
                Section::make('Data Pengunjung')
                    ->columns(2)
                    ->schema([
                        TextInput::make('jumlah_pengunjung')->label('Total Pengunjung')->numeric(),
                        TextInput::make('wisatawan_nusantara')->label('Wisatawan Nusantara')->numeric(),
                        TextInput::make('wisatawan_mancanegara')->label('Wisatawan Mancanegara')->numeric(),
                        TextInput::make('pelajar_mahasiswa')->label('Pelajar/Mahasiswa')->numeric(),
                        TextInput::make('tamu_dinas')->label('Tamu Dinas')->numeric(),
                        TextInput::make('dokumentasi_kunjungan')->label('Dokumentasi Kunjungan'),
                    ]),
                Section::make('Laporan Kerusakan')
                    ->columns(2)
                    ->schema([
                        Select::make('temuan_kerusakan')
                            ->label('Temuan Kerusakan')
                            ->options(['Ada' => 'Ada', 'Tidak Ada' => 'Tidak Ada']),
                        Textarea::make('deskripsi_kerusakan')->label('Deskripsi Kerusakan'),
                        TextInput::make('dokumentasi_kerusakan')->label('Dokumentasi Kerusakan'),
                    ]),
                Section::make('Kondisi Keamanan')
                    ->columns(2)
                    ->schema([
                        Select::make('kondisi_keamanan_situs')
                            ->label('Kondisi Keamanan')
                            ->options(['Aman dan terkendali' => 'Aman dan terkendali', 'Waspada' => 'Waspada', 'Bahaya' => 'Bahaya']),
                        Textarea::make('catatan_kondisi_keamanan')->label('Catatan Keamanan'),
                    ]),
            ]);
    }
}