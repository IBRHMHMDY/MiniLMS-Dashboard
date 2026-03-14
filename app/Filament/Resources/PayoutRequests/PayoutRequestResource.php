<?php

namespace App\Filament\Resources\PayoutRequests;

use app\Filament\Resources\PayoutRequests\Pages\ListPayoutRequests;
use App\Filament\Resources\PayoutRequests\Schemas\PayoutRequestForm;
use App\Filament\Resources\PayoutRequests\Tables\PayoutRequestsTable;
use App\Models\PayoutRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PayoutRequestResource extends Resource
{
    protected static ?string $model = PayoutRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Financials';

    public static function getModelLabel(): string
    {
        return __('Payout Request');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Payout Requests');
    }

    // المدرب هو من ينشئ طلب السحب من لوحته الخاصة وليس الأدمن
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PayoutRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayoutRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayoutRequests::route('/'),
        ];
    }
}
