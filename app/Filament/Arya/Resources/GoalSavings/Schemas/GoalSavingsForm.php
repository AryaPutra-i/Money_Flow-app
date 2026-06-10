<?php

namespace App\Filament\Arya\Resources\GoalSavings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GoalSavingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('goal_id')
                    ->label('Goal')
                    ->relationship('goal', 'Deskripsi')
                    ->required(),
                Select::make('wallet_id')
                    ->label('Wallet')
                    ->relationship('wallet', 'name')
                    ->required(),
                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->minValue(0.0)
                    ->required(),
                TextInput::make('date')
                    ->label('Date')
                    ->type('date')
                    ->required(),
                Textarea::make('notes')
                    ->label('Notes')
                    ->rows(4),
            ]);
    }
}
