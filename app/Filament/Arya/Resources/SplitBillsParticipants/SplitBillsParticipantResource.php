<?php

namespace App\Filament\Arya\Resources\SplitBillsParticipants;

use App\Filament\Arya\Resources\SplitBillsParticipants\Pages\CreateSplitBillsParticipant;
use App\Filament\Arya\Resources\SplitBillsParticipants\Pages\EditSplitBillsParticipant;
use App\Filament\Arya\Resources\SplitBillsParticipants\Pages\ListSplitBillsParticipants;
use App\Filament\Arya\Resources\SplitBillsParticipants\Schemas\SplitBillsParticipantForm;
use App\Filament\Arya\Resources\SplitBillsParticipants\Tables\SplitBillsParticipantsTable;
use App\Models\SplitBillsParticipant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SplitBillsParticipantResource extends Resource
{
    protected static ?string $model = SplitBillsParticipant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    
    protected static string | UnitEnum | null $navigationGroup = 'Transactions & Social Life';

    protected static ?string $recordTitleAttribute = 'SplitBillsParticipant';

    public static function form(Schema $schema): Schema
    {
        return SplitBillsParticipantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SplitBillsParticipantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSplitBillsParticipants::route('/'),
            'create' => CreateSplitBillsParticipant::route('/create'),
            'edit' => EditSplitBillsParticipant::route('/{record}/edit'),
        ];
    }
}
