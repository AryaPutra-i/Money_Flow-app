<?php

namespace App\Filament\Arya\Resources\SavingInvestasis;

use App\Filament\Arya\Resources\SavingInvestasis\Pages\CreateSavingInvestasi;
use App\Filament\Arya\Resources\SavingInvestasis\Pages\EditSavingInvestasi;
use App\Filament\Arya\Resources\SavingInvestasis\Pages\ListSavingInvestasis;
use App\Filament\Arya\Resources\SavingInvestasis\Schemas\SavingInvestasiForm;
use App\Filament\Arya\Resources\SavingInvestasis\Tables\SavingInvestasisTable;
use App\Models\SavingInvestasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SavingInvestasiResource extends Resource
{
    protected static ?string $model = SavingInvestasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Saving & Investments';

    protected static ?string $recordTitleAttribute = 'SavingInvestasi';

    public static function form(Schema $schema): Schema
    {
        return SavingInvestasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SavingInvestasisTable::configure($table);
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
            'index' => ListSavingInvestasis::route('/'),
            'create' => CreateSavingInvestasi::route('/create'),
            'edit' => EditSavingInvestasi::route('/{record}/edit'),
        ];
    }
}
