<?php

namespace App\Filament\Arya\Resources\Goals\Pages;

use App\Filament\Arya\Resources\Goals\GoalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGoal extends CreateRecord
{
    protected static string $resource = GoalResource::class;
}
