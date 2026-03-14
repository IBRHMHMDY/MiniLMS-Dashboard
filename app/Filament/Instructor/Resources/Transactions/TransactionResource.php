<?php

namespace App\Filament\Instructor\Resources\Transactions;

use App\Filament\Instructor\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Instructor\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Instructor\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Instructor\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Instructor\Resources\Transactions\Tables\TransactionsTable;
use App\Models\Transaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Financials';

    public static function getModelLabel(): string
    {
        return __('Transaction');
    }

    public static function getPluralModelLabel(): string
    {
        return __('My Earnings');
    }
    
    public static function canCreate(): bool
    {
        return false;
    }

    // 2. العزل المعماري للبيانات: إجبار الاستعلام على جلب مبيعات المدرب الحالي فقط
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('instructor_id', Auth::id());
    }
    public static function form(Schema $schema): Schema
    {
        return TransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
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
            'index' => ListTransactions::route('/'),
        ];
    }
}
