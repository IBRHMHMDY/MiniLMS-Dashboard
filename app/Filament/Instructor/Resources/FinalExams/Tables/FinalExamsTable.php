<?php

namespace App\Filament\Instructor\Resources\FinalExams\Tables;

use App\Models\Course;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinalExamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label(__('Image'))
                    ->circular(),

                TextColumn::make('title')
                    ->label(__('Course Title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->badge()
                    ->color('gray'),

                // عمود يوضح هل الكورس يمتلك امتحاناً نهائياً أم لا
                IconColumn::make('finalExam')
                    ->label(__('Exam Status'))
                    ->boolean()
                    ->getStateUsing(fn (Course $record): bool => $record->finalExam !== null)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(fn(Course $record) => $record->finalExam ? __('Edit Exam') : __('Create Exam'))
                    ->icon('heroicon-o-academic-cap')
                    ->color(fn(Course $record) => $record->finalExam ? 'primary' : 'success'),
            ]);
    }
}
