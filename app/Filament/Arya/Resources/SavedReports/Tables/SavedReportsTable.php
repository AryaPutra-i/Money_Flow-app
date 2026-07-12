<?php

namespace App\Filament\Arya\Resources\SavedReports\Tables;

use App\Models\createSavedReport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SavedReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workspace.name')->label('Workspace')->sortable()->searchable(),
                TextColumn::make('nama_laporan')->label('Nama Laporan')->sortable()->searchable(),
                TextColumn::make('tipe_grafik')->label('Forma Visualisasi Grafik')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                TextColumn::make('created_at')->label('Dibuat Pada')->date('d M Y'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->label('Hapus Data Terpilih')
                    ->modalHeading('Hapus Goals Terpilih') // Judul di dalam modal
                    ->modalDescription('Apakah Anda yakin ingin menghapus semua data yang dicentang? Tindakan ini tidak dapat dibatalkan.')  // Teks deskripsi modal
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang'),

                ])
                ->Label('Aksi'),
            ]);
    }
}
