<?php

namespace App\Filament\Arya\Resources\SplitBills\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SplitBillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('transaction_id')
                    ->relationship('transaction', 'id')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->minValue(0.0)
                    ->numeric(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'completed' => 'Completed']),
            ]);
    }
}
