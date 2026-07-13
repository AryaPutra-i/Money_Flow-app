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
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;


class SplitBillsParticipantResource extends Resource
{
    protected static ?string $model = SplitBillsParticipant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    
    protected static string | UnitEnum | null $navigationGroup = 'Transactions & Social Life';

    protected static ?string $recordTitleAttribute = 'friend_name';

    protected ?string $heading = 'Split Bills Participant';

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable {
        return $record->friend_name;
    }

    public static function getGloballySearchableAttributes(): array {
        return ['friend_name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array {
        return [
            'amount_due' => $record->amount_due,
            'is_paid' => $record->is_paid,
        ];
    }

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
