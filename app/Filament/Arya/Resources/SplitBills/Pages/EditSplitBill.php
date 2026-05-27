<?php

namespace App\Filament\Arya\Resources\SplitBills\Pages;

use App\Filament\Arya\Resources\SplitBills\SplitBillResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSplitBill extends EditRecord
{
    protected static string $resource = SplitBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
