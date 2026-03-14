<?php

namespace App\Filament\Instructor\Resources\PayoutRequests\Pages;

use App\Filament\Instructor\Resources\PayoutRequests\PayoutRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayoutRequest extends CreateRecord
{
    protected static string $resource = PayoutRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
