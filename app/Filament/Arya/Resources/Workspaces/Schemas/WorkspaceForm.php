<?php

namespace App\Filament\Arya\Resources\Workspaces\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;

class WorkspaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_account_id')
                    ->default(auth()->id()),
                TextInput::make('name'),
                Select::make('type')
                    ->options(['personal' => 'Personal', 'organization' => 'Organization'])
                    ->default('personal')
                    ->required(),
            ]);
    }
}
