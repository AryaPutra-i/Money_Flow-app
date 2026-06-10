<?php

namespace App\Filament\Arya\Resources\GoalSavings\Pages;

use App\Filament\Arya\Resources\GoalSavings\GoalSavingsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGoalSavings extends EditRecord
{
    protected static string $resource = GoalSavingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
