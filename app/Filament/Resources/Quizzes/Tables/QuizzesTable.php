<?php

namespace App\Filament\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizzesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.title')
                    ->label('الكورس')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->label('عنوان الاختبار')
                    ->searchable(),
                TextColumn::make('questions_count')
                    ->counts('questions') // يحسب عدد الأسئلة تلقائياً
                    ->label('عدد الأسئلة')
                    ->badge(),
                TextColumn::make('pass_mark')
                    ->label('درجة النجاح')
                    ->suffix('%'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
