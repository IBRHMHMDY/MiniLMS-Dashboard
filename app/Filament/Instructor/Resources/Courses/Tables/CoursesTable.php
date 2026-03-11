<?php

namespace App\Filament\Instructor\Resources\Courses\Tables;

use App\Filament\Instructor\Resources\Lessons\LessonResource;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Cover')
                    ->square()
                    ->imageSize(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label('Course Name')
                    ->description(fn (Course $record): string => $record->category?->name ?? 'No Category')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('lessons_count')
                    ->label('Lessons')
                    ->icon('heroicon-o-book-open') // أيقونة الكتاب المعبرة عن الدروس
                    ->sortable()
                    ->badge() // جعل الرقم يظهر كشريط (Badge) أنيق
                    ->color('info'), // اللون الأزرق لتمييزه
                TextColumn::make('enrollments_count')
                    ->label('Students Enrolled')
                    ->icon('heroicon-o-users')
                    ->sortable(),
                IconColumn::make('is_free')
                    ->label('Free')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                TextColumn::make('price')
                    ->label('Price')
                    ->money()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->multiple()
                    ->preload(),
                TernaryFilter::make('is_published')
                    ->label('Publish Status'),
                    
                TernaryFilter::make('is_free')
                    ->label('Price Status')
                    ->trueLabel('Free Courses')
                    ->falseLabel('Paid Courses'),
            ])
            ->recordActions([
                ViewAction::make()->label('Details')->color('gray'),
                EditAction::make()->color('primary'),
                Action::make('manage_lessons')
                    ->label('Manage Lessons')
                    ->icon('heroicon-o-document-text')
                    ->color('warning')
                    ->url(fn (Course $record): string => LessonResource::getUrl('index', ['course_id' => $record->id])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
