<?php

namespace App\Filament\Arya\Resources\SavingInvestasis\Pages;

use App\Filament\Arya\Resources\SavingInvestasis\SavingInvestasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSavingInvestasi extends EditRecord
{
    protected static string $resource = SavingInvestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
