<?php

namespace App\Filament\Arya\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                TextInput::make('name_category'),
                Select::make('type_category')
                    ->options(['income' => 'Income', 'expense' => 'Expense'])
                    ->default('expense')
                    ->required(),
            ]);
    }
}
