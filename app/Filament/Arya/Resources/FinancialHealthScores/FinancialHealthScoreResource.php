<?php

namespace App\Filament\Arya\Resources\FinancialHealthScores;

use App\Filament\Arya\Resources\FinancialHealthScores\Pages\CreateFinancialHealthScore;
use App\Filament\Arya\Resources\FinancialHealthScores\Pages\EditFinancialHealthScore;
use App\Filament\Arya\Resources\FinancialHealthScores\Pages\ListFinancialHealthScores;
use App\Filament\Arya\Resources\FinancialHealthScores\Schemas\FinancialHealthScoreForm;
use App\Filament\Arya\Resources\FinancialHealthScores\Tables\FinancialHealthScoresTable;
use App\Models\FinancialHealthScore;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use unitEnum;

class FinancialHealthScoreResource extends Resource
{
    protected static ?string $model = FinancialHealthScore::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Financial Analysis';

    protected ?string $heading = 'Financial Health Score';


    public static function form(Schema $schema): Schema
    {
        return FinancialHealthScoreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinancialHealthScoresTable::configure($table);
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
            'index' => ListFinancialHealthScores::route('/'),
            'create' => CreateFinancialHealthScore::route('/create'),
            'edit' => EditFinancialHealthScore::route('/{record}/edit'),
        ];
    }
}
