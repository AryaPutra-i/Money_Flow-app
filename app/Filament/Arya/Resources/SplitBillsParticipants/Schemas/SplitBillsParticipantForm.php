<?php

namespace App\Filament\Arya\Resources\SplitBillsParticipants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SplitBillsParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('split_bill_id')
                    ->relationship('splitBill', 'id')
                    ->required(),
                TextInput::make('friend_name'),
                TextInput::make('amount_due')
                    ->required()
                    ->numeric(),
                Toggle::make('is_paid')
                    ->required(),
            ]);
    }
}
