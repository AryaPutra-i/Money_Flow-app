<?php

namespace App\Filament\Arya\Resources\Wallets;

use App\Filament\Arya\Resources\Wallets\Pages\CreateWallet;
use App\Filament\Arya\Resources\Wallets\Pages\EditWallet;
use App\Filament\Arya\Resources\Wallets\Pages\ListWallets;
use App\Filament\Arya\Resources\Wallets\Schemas\WalletForm;
use App\Filament\Arya\Resources\Wallets\Tables\WalletsTable;
use App\Models\Wallet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;


class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWallet;

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $recordTitleAttribute = 'name';

    protected ?string $heading = 'Wallet';

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array {
        return ['name', 'workspace.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array {
        return [
            'Workspace' => $record->workspace->name,
            'Balance' => $record->balance,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return WalletForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WalletsTable::configure($table);
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
            'index' => ListWallets::route('/'),
            'create' => CreateWallet::route('/create'),
            'edit' => EditWallet::route('/{record}/edit'),
        ];
    }
}
