<?php

namespace App\Filament\Arya\Resources\SubscriptionTransactions;

use App\Filament\Arya\Resources\SubscriptionTransactions\Pages\CreateSubscriptionTransaction;
use App\Filament\Arya\Resources\SubscriptionTransactions\Pages\EditSubscriptionTransaction;
use App\Filament\Arya\Resources\SubscriptionTransactions\Pages\ListSubscriptionTransactions;
use App\Filament\Arya\Resources\SubscriptionTransactions\Schemas\SubscriptionTransactionForm;
use App\Filament\Arya\Resources\SubscriptionTransactions\Tables\SubscriptionTransactionsTable;
use App\Models\SubscriptionTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use unitEnum;

class SubscriptionTransactionResource extends Resource
{
    protected static ?string $model = SubscriptionTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static string | UnitEnum | null $navigationGroup = 'Transactions & Social Life';


    protected static ?string $recordTitleAttribute = 'subcriptionTransaction';

    public static function form(Schema $schema): Schema
    {
        return SubscriptionTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionTransactionsTable::configure($table);
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
            'index' => ListSubscriptionTransactions::route('/'),
            'create' => CreateSubscriptionTransaction::route('/create'),
            'edit' => EditSubscriptionTransaction::route('/{record}/edit'),
        ];
    }
}
