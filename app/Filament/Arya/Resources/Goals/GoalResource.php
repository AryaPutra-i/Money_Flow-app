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

class GoalResource extends Resource
{
    protected static ?string $model = Goal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string | UnitEnum | null $navigationGroup = 'Planning & Obligations';

    protected static ?string $recordTitleAttribute = 'goal';

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
