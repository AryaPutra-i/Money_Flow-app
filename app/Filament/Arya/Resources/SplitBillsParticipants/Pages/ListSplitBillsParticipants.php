<?php

namespace App\Filament\Arya\Resources\SplitBillsParticipants\Pages;

use App\Filament\Arya\Resources\SplitBillsParticipants\SplitBillsParticipantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSplitBillsParticipants extends ListRecords
{
    protected static string $resource = SplitBillsParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
