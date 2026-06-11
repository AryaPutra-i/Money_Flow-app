<?php

namespace App\Filament\Arya\Resources\SavingInvestasis\Pages;

use App\Filament\Arya\Resources\SavingInvestasis\SavingInvestasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSavingInvestasis extends ListRecords
{
    protected static string $resource = SavingInvestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
