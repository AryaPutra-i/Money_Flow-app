<?php

namespace App\Filament\Arya\Resources\Budgets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BudgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'id')
                    ->required(),
                TextInput::make('limit_amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('moonth_year')
                    ->required(),
            ]);
    }
}
