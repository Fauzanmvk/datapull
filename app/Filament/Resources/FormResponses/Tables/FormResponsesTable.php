<?php

namespace App\Filament\Resources\FormResponses\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\FormResponse;

class FormResponsesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('submitted_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('cagar_budaya')
                    ->label('Cagar Budaya')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_petugas')
                    ->label('Nama Petugas')
                    ->searchable(),
                TextColumn::make('jumlah_pengunjung')
                    ->label('Total Pengunjung')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('temuan_kerusakan')
                    ->label('Temuan Kerusakan')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'Ada' => 'danger',
                        'Tidak Ada' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('kondisi_keamanan_situs')
                    ->label('Kondisi Keamanan')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'Aman dan terkendali' => 'success',
                        'Aman namun perlu perhatian' => 'warning',
                        'Waspada' => 'warning',
                        'Bahaya' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                SelectFilter::make('cagar_budaya')
                    ->label('Cagar Budaya')
                    ->options(fn() => FormResponse::distinct()
                        ->pluck('cagar_budaya', 'cagar_budaya')
                        ->toArray()),
                SelectFilter::make('temuan_kerusakan')
                    ->label('Temuan Kerusakan')
                    ->options(['Ada' => 'Ada', 'Tidak Ada' => 'Tidak Ada']),
                Filter::make('bulan')
                    ->label('Bulan')
                    ->form([
                        Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                '1' => 'Januari', '2' => 'Februari', '3' => 'Maret',
                                '4' => 'April', '5' => 'Mei', '6' => 'Juni',
                                '7' => 'Juli', '8' => 'Agustus', '9' => 'September',
                                '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                            ]),
                        Select::make('tahun')
                            ->label('Tahun')
                            ->options(fn() => FormResponse::selectRaw('YEAR(submitted_at) as year')
                                ->distinct()
                                ->orderBy('year', 'desc')
                                ->pluck('year', 'year')
                                ->toArray()),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['bulan'])) {
                            $query->whereMonth('submitted_at', $data['bulan']);
                        }
                        if (!empty($data['tahun'])) {
                            $query->whereYear('submitted_at', $data['tahun']);
                        }
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Permanen')
                    ->modalDescription('Data ini akan dihapus permanen dan tidak bisa dikembalikan!')
                    ->modalSubmitActionLabel('Ya, Hapus!')
                    ->action(fn ($record) => $record->forceDelete()),
            ])
            ->toolbarActions([
                Action::make('export_all')
                    ->label('Download Semua')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url('/export-responses')
                    ->openUrlInNewTab(),
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('export_selected')
                        ->label('Download Terpilih')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->join(',');
                            return redirect('/export-responses?ids=' . $ids);
                        }),
                    \Filament\Actions\BulkAction::make('force_delete_selected')
                        ->label('Hapus Permanen Terpilih')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Permanen')
                        ->modalDescription('Data yang dipilih akan dihapus permanen!')
                        ->modalSubmitActionLabel('Ya, Hapus!')
                        ->action(fn ($records) => $records->each->forceDelete()),
                ]),
            ]);
    }
}