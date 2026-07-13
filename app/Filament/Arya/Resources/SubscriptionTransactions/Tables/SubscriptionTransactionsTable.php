<?php

namespace App\Filament\Arya\Resources\SubscriptionTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscriptionTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_transaksi')
                    ->label('Transaction Name')
                    ->searchable(),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('frekuensi')
                    ->label('Frequency')
                    ->badge(),
                TextColumn::make('tanggal_mulai')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_eksekusi_berikutnya')
                    ->label('Next Execution Date')
                    ->date()
                    ->sortable(),
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
