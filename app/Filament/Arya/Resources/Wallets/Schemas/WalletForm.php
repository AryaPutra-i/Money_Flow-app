<?php

namespace App\Filament\Arya\Resources\Wallets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                TextInput::make('name'),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
