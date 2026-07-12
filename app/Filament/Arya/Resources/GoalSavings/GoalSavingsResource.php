<?php

namespace App\Filament\Arya\Resources\GoalSavings;

use App\Filament\Arya\Resources\GoalSavings\Pages\CreateGoalSavings;
use App\Filament\Arya\Resources\GoalSavings\Pages\EditGoalSavings;
use App\Filament\Arya\Resources\GoalSavings\Pages\ListGoalSavings;
use App\Filament\Arya\Resources\GoalSavings\Schemas\GoalSavingsForm;
use App\Filament\Arya\Resources\GoalSavings\Tables\GoalSavingsTable;
use App\Models\GoalSaving;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GoalSavingsResource extends Resource
{
    protected static ?string $model = GoalSaving::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Saving & Investments';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return GoalSavingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoalSavingsTable::configure($table);
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
            'index' => ListGoalSavings::route('/'),
            'create' => CreateGoalSavings::route('/create'),
            'edit' => EditGoalSavings::route('/{record}/edit'),
        ];
    }
}
