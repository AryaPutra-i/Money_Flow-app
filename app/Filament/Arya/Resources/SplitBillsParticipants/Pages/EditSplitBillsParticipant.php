<?php

namespace App\Filament\Arya\Resources\SplitBillsParticipants\Pages;

use App\Filament\Arya\Resources\SplitBillsParticipants\SplitBillsParticipantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSplitBillsParticipant extends EditRecord
{
    protected static string $resource = SplitBillsParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
