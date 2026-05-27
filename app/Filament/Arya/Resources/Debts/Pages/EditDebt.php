<?php

namespace App\Filament\Arya\Resources\Debts\Pages;

use App\Filament\Arya\Resources\Debts\DebtResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDebt extends EditRecord
{
    protected static string $resource = DebtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
