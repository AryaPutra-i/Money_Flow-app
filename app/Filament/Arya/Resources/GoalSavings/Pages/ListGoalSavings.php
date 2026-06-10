<?php

namespace App\Filament\Arya\Resources\GoalSavings\Pages;

use App\Filament\Arya\Resources\GoalSavings\GoalSavingsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoalSavings extends ListRecords
{
    protected static string $resource = GoalSavingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
