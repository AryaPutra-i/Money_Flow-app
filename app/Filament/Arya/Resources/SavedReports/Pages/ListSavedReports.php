<?php

namespace App\Filament\Arya\Resources\SavedReports\Pages;

use App\Filament\Arya\Resources\SavedReports\SavedReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSavedReports extends ListRecords
{
    protected static string $resource = SavedReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
