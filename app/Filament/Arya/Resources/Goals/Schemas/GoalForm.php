<?php

namespace App\Filament\Arya\Resources\Goals\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class GoalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                TextInput::make('Deskripsi'),
                TextInput::make('target_amount')
                    ->required()
                    ->numeric()
                    ->minValue(0.0),
                Hidden::make('current_amount')
                    ->default(0.0),
            ]);
    }
}
