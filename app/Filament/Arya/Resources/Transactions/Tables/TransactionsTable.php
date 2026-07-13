<?php

namespace App\Filament\Arya\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workspace.name')
                    ->sortable(),
                TextColumn::make('wallet.name')
                    ->sortable(),
                TextColumn::make('category.id')
                    ->sortable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->prefix('Rp')
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('proof_path')
                    ->searchable(),
                IconColumn::make('is_recurring')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
