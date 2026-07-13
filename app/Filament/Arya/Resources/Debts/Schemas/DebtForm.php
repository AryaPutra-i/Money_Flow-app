<?php

namespace App\Filament\Arya\Resources\Debts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DebtForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                Select::make('type')
                    ->options(['debt' => 'Debt', 'receivable' => 'Receivable'])
                    ->default('debt')
                    ->required(),
                TextInput::make('person_name')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->minValue(0.0)
                    ->numeric(),
                Select::make('status')
                    ->options(['unpaid' => 'Unpaid', 'paid' => 'Paid'])
                    ->default('unpaid')
                    ->required(),
            ]);
    }
}
