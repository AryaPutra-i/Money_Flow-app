<?php

namespace App\Filament\Arya\Resources\SubscriptionTransactions\Pages;

use App\Filament\Arya\Resources\SubscriptionTransactions\SubscriptionTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionTransaction extends EditRecord
{
    protected static string $resource = SubscriptionTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
