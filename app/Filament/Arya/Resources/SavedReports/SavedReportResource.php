<?php

namespace App\Filament\Arya\Resources\SavedReports;

use App\Filament\Arya\Resources\SavedReports\Pages\CreateSavedReport;
use App\Filament\Arya\Resources\SavedReports\Pages\EditSavedReport;
use App\Filament\Arya\Resources\SavedReports\Pages\ListSavedReports;
use App\Filament\Arya\Resources\SavedReports\Schemas\SavedReportForm;
use App\Filament\Arya\Resources\SavedReports\Tables\SavedReportsTable;
use App\Models\SavedReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use unitEnum;

class SavedReportResource extends Resource
{
    protected static ?string $model = SavedReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Financial Analysis';


    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return SavedReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SavedReportsTable::configure($table);
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
            'index' => ListSavedReports::route('/'),
            'create' => CreateSavedReport::route('/create'),
            'edit' => EditSavedReport::route('/{record}/edit'),
        ];
    }
}
