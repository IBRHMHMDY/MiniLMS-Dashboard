<?php

namespace App\Filament\Instructor\Resources\PayoutRequests\Pages;

use App\Filament\Instructor\Resources\PayoutRequests\PayoutRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayoutRequests extends ListRecords
{
    protected static string $resource = PayoutRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('Request Payout'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
