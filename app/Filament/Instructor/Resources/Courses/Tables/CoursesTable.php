<?php

namespace App\Filament\Instructor\Resources\Courses\Tables;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('instructor.name')->sortable(),
                TextColumn::make('category.name')->sortable(),
                TextColumn::make('price')->money()->sortable(),
                ImageColumn::make('image_path'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('manage_lessons')
                    ->label('الدروس')
                    ->icon('heroicon-o-film')
                    ->color('info')
                    ->url(fn ($record) => CourseResource::getUrl('lessons', ['record' => $record])),
                Action::make('manage_quizzes')
                    ->label('الاختبارات')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->url(fn ($record) => CourseResource::getUrl('quizzes', ['record' => $record])),
                // 4. زر الحذف
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
