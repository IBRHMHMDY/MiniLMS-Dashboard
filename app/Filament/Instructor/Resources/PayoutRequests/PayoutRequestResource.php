<?php

namespace App\Filament\Instructor\Resources\PayoutRequests;

use App\Filament\Instructor\Resources\PayoutRequests\Pages\CreatePayoutRequest;
use App\Filament\Instructor\Resources\PayoutRequests\Pages\EditPayoutRequest;
use App\Filament\Instructor\Resources\PayoutRequests\Pages\ListPayoutRequests;
use App\Filament\Instructor\Resources\PayoutRequests\Schemas\PayoutRequestForm;
use App\Filament\Instructor\Resources\PayoutRequests\Tables\PayoutRequestsTable;
use App\Models\PayoutRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
        return __('Withdrawals');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('instructor_id', Auth::id());
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
            'create' => CreatePayoutRequest::route('/create'),
        ];
    }
}
