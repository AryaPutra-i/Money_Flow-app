<?php

namespace App\Filament\Arya\Resources\SavedReports\Pages;

use App\Filament\Arya\Resources\SavedReports\SavedReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSavedReport extends EditRecord
{
    protected static string $resource = SavedReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
