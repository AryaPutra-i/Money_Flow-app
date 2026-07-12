<?php

namespace App\Filament\Arya\Resources\SavedReports\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SavedReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('nama_laporan')
                    ->label('Nama Laporan')
                    ->required()
                    ->placeholder('contoh: Analisis Kuartal Pertama'),
                Select::make('tipe_grafik')
                    ->label('Forma Visualisasi Grafik')
                    ->required()
                    ->options([
                        'pie' => 'Pie/Lingkaran',
                        'line' => 'Line / Tren Garis',
                        'bar' => 'Bar / Batang Kolom',
                    ]),
                KeyValue::make('filter_data')
                    ->label('Parameter Filter Kustom')
                    ->keyLabel('Kunci Filter (Request Key)')
                    ->valueLabel('Nilai Filter')
                    ->columnSpanFull(),
            ]);
    }
}
