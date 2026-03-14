<?php

namespace App\Filament\Instructor\Resources\PayoutRequests\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PayoutRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->label(__('Amount to Withdraw'))
                    ->numeric()
                    ->required()
                    ->minValue(10) // الحد الأدنى للسحب
                    ->prefix('$')
                    ->columnSpanFull(),
                    
                Hidden::make('instructor_id')
                    ->default(fn () => Auth::id()),
            ]);
    }
}
