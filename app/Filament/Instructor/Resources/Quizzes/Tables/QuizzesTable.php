<?php

namespace App\Filament\Instructor\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QuizzesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Quiz Title')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('pass_mark')
                    ->label('Passing')
                    ->default(50)
                    ->suffix('%')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->badge()
                    ->icon('heroicon-o-question-mark-circle')
                    ->color('info'),

                ToggleColumn::make('is_published')
                    ->label('Published')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit Quiz')
                    ->modalWidth('full')
                    ->modalHeading('Edit Quiz'),

                
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
