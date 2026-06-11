<?php

namespace App\Filament\Arya\Resources\SubscriptionTransactions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class SubscriptionTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->label('Workspace')
                    ->relationship('workspace', 'name')
                    ->required(),
                Select::make('wallet_id')
                    ->label('Wallet')
                    ->relationship('wallet', 'name')
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name_category')
                    ->required(),
                TextInput::make('nama_transaksi')
                    ->label('Transaction Name')
                    ->required(),
                TextInput::make('nominal')
                    ->label('Nominal harga')
                    ->numeric()
                    ->required()
                    ->minValue(0.0),
                Select::make('frekuensi')
                    ->label('Frekuensi')
                    ->options([
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->default('monthly')
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->default(now())
                    ->required(),
                DatePicker::make('tanggal_eksekusi_berikutnya')
                    ->label('Tanggal Eksekusi Berikutnya')
                    ->default(now()->addMonth(1)->toDateString())
                    ->required(),
            ]);
    }
}
