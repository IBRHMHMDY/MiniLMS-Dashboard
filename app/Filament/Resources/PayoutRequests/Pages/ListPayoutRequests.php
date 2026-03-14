<?php

namespace App\Filament\Resources\PayoutRequests\Pages;


use app\Filament\Resources\PayoutRequests\PayoutRequestResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPayoutRequests extends ListRecords
{
    protected static string $resource = PayoutRequestResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('All Requests')),
            
            'pending' => Tab::make(__('Pending'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge($this->getModel()::where('status', 'pending')->count())
                ->badgeColor('warning'),
                
            'paid' => Tab::make(__('Paid'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid')),
                
            'rejected' => Tab::make(__('Rejected'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),
        ];
    }
}