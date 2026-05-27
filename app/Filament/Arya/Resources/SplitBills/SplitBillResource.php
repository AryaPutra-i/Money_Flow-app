<?php

namespace App\Filament\Arya\Resources\SplitBills;

use App\Filament\Arya\Resources\SplitBills\Pages\CreateSplitBill;
use App\Filament\Arya\Resources\SplitBills\Pages\EditSplitBill;
use App\Filament\Arya\Resources\SplitBills\Pages\ListSplitBills;
use App\Filament\Arya\Resources\SplitBills\Schemas\SplitBillForm;
use App\Filament\Arya\Resources\SplitBills\Tables\SplitBillsTable;
use App\Models\SplitBill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SplitBillResource extends Resource
{
    protected static ?string $model = SplitBill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string | UnitEnum | null $navigationGroup = 'Transactions & Social Life';

    protected static ?string $recordTitleAttribute = 'SplitBill';

    public static function form(Schema $schema): Schema
    {
        return SplitBillForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SplitBillsTable::configure($table);
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
            'index' => ListSplitBills::route('/'),
            'create' => CreateSplitBill::route('/create'),
            'edit' => EditSplitBill::route('/{record}/edit'),
        ];
    }
}
