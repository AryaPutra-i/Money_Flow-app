<?php

namespace App\Filament\Arya\Resources\SplitBills\Pages;

use App\Filament\Arya\Resources\SplitBills\SplitBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSplitBills extends ListRecords
{
    protected static string $resource = SplitBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
