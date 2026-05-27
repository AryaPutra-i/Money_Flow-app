<?php

namespace App\Filament\Arya\Resources\Transactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                Select::make('wallet_id')
                    ->relationship('wallet', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name_category')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->options(['income' => 'Income', 'expense' => 'Expense', 'transfer' => 'Transfer'])
                    ->default('expense')
                    ->required(),
                DatePicker::make('transaction_date')
                    ->required(),
                TextInput::make('proof_path'),
                Toggle::make('is_recurring')
                    ->required(),
            ]);
    }
}
