<?php

namespace App\Filament\Arya\Resources\Debts;

use App\Filament\Arya\Resources\Debts\Pages\CreateDebt;
use App\Filament\Arya\Resources\Debts\Pages\EditDebt;
use App\Filament\Arya\Resources\Debts\Pages\ListDebts;
use App\Filament\Arya\Resources\Debts\Schemas\DebtForm;
use App\Filament\Arya\Resources\Debts\Tables\DebtsTable;
use App\Models\Debt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;

    protected static string | UnitEnum | null $navigationGroup = 'Planning & Obligations';

    protected static ?string $recordTitleAttribute = 'debt';

    public static function form(Schema $schema): Schema
    {
        return DebtForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DebtsTable::configure($table);
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
            'index' => ListDebts::route('/'),
            'create' => CreateDebt::route('/create'),
            'edit' => EditDebt::route('/{record}/edit'),
        ];
    }
}
