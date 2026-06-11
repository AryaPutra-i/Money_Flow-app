<?php

namespace App\Filament\Arya\Resources\FinancialHealthScores\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FinancialHealthScoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->disabled(),
                TextInput::make('score')
                    ->label('skor akhir')
                    ->suffix('%')
                    ->disabled(),
                KeyValue::make('rincian_metrik')
                    ->label('Rincian Breakdown Indikator')
                    ->keyLabel('Komponen Indikator')
                    ->valueLabel('hasil perhitugan')
                    ->columnSpanFull()
                    ->disabled(),
            ]);
    }
}
