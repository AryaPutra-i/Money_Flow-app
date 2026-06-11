<?php

namespace App\Filament\Arya\Resources\SavingInvestasis\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Iluminate\Support\Carbon;

class SavingInvestasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('wallet_id')
                    ->label('Wallet')
                    ->relationship('wallet', 'name')
                    ->required(),
                Select::make('workspace_id')
                    ->label('Workspace')
                    ->relationship('workspace', 'name')
                    ->required(),
                Select::make('intrumen')
                    ->label('Intrumen')
                    ->options([
                        'saham' => 'Saham',
                        'obligasi' => 'Obligasi',
                        'reksa dana' => 'Reksa Dana',
                        'emas' => 'Emas',
                        'properti' => 'Properti',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),
                TextInput::make('nama_instrumen')
                    ->label('Nama Instrumen')
                    ->required(),
                TextInput::make('nominal_modal')
                    ->label('Nominal Modal')
                    ->numeric()
                    ->minValue(0.0)
                    ->required(),
                TextInput::make('estimasi_return')
                    ->label('Estimasi Return')
                    ->numeric()
                    ->minValue(0.0)
                    ->maxValue(100.0)
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(now()->toDateString()),
                DatePicker::make('tanggal_jatuh_tempo')
                    ->label('Tanggal Jatuh Tempo')
                    ->default(now()->addMonths(12)->toDateString()),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'selesai' => 'Selesai',
                        'jual' => 'Jual',
                    ])
                    ->required(),
                    
                 
            ]);
    }
}
