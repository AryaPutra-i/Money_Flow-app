<?php

namespace App\Filament\Arya\Resources\FinancialHealthScores\Pages;

use App\Filament\Arya\Resources\FinancialHealthScores\FinancialHealthScoreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFinancialHealthScore extends EditRecord
{
    protected static string $resource = FinancialHealthScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
