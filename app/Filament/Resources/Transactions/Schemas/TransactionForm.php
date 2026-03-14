<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                Select::make('instructor_id')
                    ->relationship('instructor', 'name')
                    ->required(),
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('platform_commission')
                    ->required()
                    ->numeric(),
                TextInput::make('instructor_commission')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_gateway_reference')
                    ->default(null),
            ]);
    }
}
