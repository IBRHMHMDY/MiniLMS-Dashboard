<?php

namespace App\Filament\Instructor\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('ID'))
                    ->sortable(),
                TextColumn::make('course.title')
                    ->label(__('Course'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('student.name')
                    ->label(__('Student'))
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('Course Price'))
                    ->money('USD') // يمكنك تغيير العملة إلى EGP إذا لزم الأمر
                    ->sortable()
                    ->color('gray'),
                TextColumn::make('instructor_commission')
                    ->label(__('My Commission'))
                    ->money('USD')
                    ->sortable()
                    ->color('success') // تمييز أرباح المدرب باللون الأخضر
                    ->weight('bold'),
                TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
