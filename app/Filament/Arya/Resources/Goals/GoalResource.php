<?php

namespace App\Filament\Arya\Resources\Goals;

use App\Filament\Arya\Resources\Goals\Pages\CreateGoal;
use App\Filament\Arya\Resources\Goals\Pages\EditGoal;
use App\Filament\Arya\Resources\Goals\Pages\ListGoals;
use App\Filament\Arya\Resources\Goals\Schemas\GoalForm;
use App\Filament\Arya\Resources\Goals\Tables\GoalsTable;
use App\Models\Goal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;


class GoalResource extends Resource
{
    protected static ?string $model = Goal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string | UnitEnum | null $navigationGroup = 'Planning & Obligations';

    protected static ?string $recordTitleAttribute = 'Deskripsi';

        protected ?string $heading = 'Goal';


    public static function getGloballySearchableAttrributes(): array
    {
        return [
            'Deskripsi',
            'workspace.name',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string |Htmlable
    {
        return $record->Deskripsi;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Workspace' => $record->workspace->name,
            'Target Amount' => $record->target_amount,
            'Current Amount' => $record->current_amount,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return GoalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoalsTable::configure($table);
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
            'index' => ListGoals::route('/'),
            'create' => CreateGoal::route('/create'),
            'edit' => EditGoal::route('/{record}/edit'),
        ];
    }
}
