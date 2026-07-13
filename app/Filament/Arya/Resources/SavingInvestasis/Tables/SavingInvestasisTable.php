<?php

namespace App\Filament\Arya\Resources\SavingInvestasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SavingInvestasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('wallet.name')
                    ->label('Wallet')
                    ->searchable(),
                TextColumn::make('workspace.name')
                    ->label('Workspace')
                    ->searchable(),
                TextColumn::make('intrumen')
                    ->label('Instrument')
                    ->searchable(),
                TextColumn::make('nama_instrumen')
                    ->label('Instrument Name')
                    ->searchable(),
                TextColumn::make('nominal_modal')
                    ->label('Nominal Capital')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estimasi_return')
                    ->label('Expected Return (%)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_mulai')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Maturity Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ->label('Aksi'),
            ]);
    }
}
