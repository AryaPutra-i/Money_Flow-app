<?php

namespace App\Filament\Arya\Resources\GoalSavings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;

class GoalSavingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('goal.Deskripsi')
                    ->label('Goal'),
                TextColumn::make('wallet.name')
                    ->label('Wallet'),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notes')
                    ->wrap(),
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
                ->Label('Aksi'),
            ]);
    }
}
