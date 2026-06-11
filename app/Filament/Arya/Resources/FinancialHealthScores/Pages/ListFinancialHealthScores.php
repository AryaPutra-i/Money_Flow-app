<?php

namespace App\Filament\Arya\Resources\FinancialHealthScores\Pages;

use App\Filament\Arya\Resources\FinancialHealthScores\FinancialHealthScoreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinancialHealthScores extends ListRecords
{
    protected static string $resource = FinancialHealthScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
