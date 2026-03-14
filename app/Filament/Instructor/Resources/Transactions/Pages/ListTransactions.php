<?php

namespace App\Filament\Instructor\Resources\Transactions\Pages;

use App\Filament\Instructor\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
