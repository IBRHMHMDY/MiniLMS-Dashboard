<?php

namespace App\Filament\Instructor\Resources\Courses\Tables;

use App\Enums\CourseStatus;
use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Resources\Lessons\LessonResource;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
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

                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('usd')
                    ->sortable()
                    ->getStateUsing(fn (Course $record) => $record->is_free ? 0 : $record->price)
                    ->badge(fn (Course $record) => $record->is_free)
                    ->color(fn (Course $record) => $record->is_free ? 'success' : 'primary')
                    ->formatStateUsing(fn ($state, Course $record) => $record->is_free ? __('Free') : '$' . $state),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (CourseStatus $state): string => match ($state) {
                        CourseStatus::DRAFT => 'gray',
                        CourseStatus::PUBLISHED => 'success',
                        CourseStatus::ARCHIVED => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(CourseStatus::class),

                SelectFilter::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                Action::make('manage_content')
                    ->label(__('Manage Content'))
                    ->icon('heroicon-o-queue-list')
                    ->color('info')
                    ->button()
                    // 🚨 التعديل هنا: التوجيه لصفحة curriculum
                    ->url(fn (Course $record): string => CourseResource::getUrl('curriculum', ['record' => $record->id])),

                // 2. زر تعديل إعدادات الكورس الأساسية
                EditAction::make()
                    ->label(__('Edit'))
                    ->icon('heroicon-o-pencil')
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
