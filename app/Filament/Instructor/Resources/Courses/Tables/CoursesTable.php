<?php

namespace App\Filament\Instructor\Resources\Courses\Tables;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\ActionGroup as ActionsActionGroup;
use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 🌟 صورة الكورس كـ Avatar
                ImageColumn::make('image_path')
                    ->label('الغلاف')
                    ->circular(),

                // 🌟 دمج العنوان مع اسم القسم أسفله لترتيب الجدول
                TextColumn::make('title')
                    ->label('الكورس')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->category->name ?? 'بدون قسم'),

                // 🌟 إحصائيات سريعة: عدد الدروس
                TextColumn::make('lessons_count')
                    ->counts('lessons')
                    ->label('الدروس')
                    ->badge()
                    ->color('info'),

                // 🌟 تسعير ذكي مع شارات ملونة
                TextColumn::make('price')
                    ->label('السعر')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'primary' : 'success')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state.' EGP' : 'مجاني'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // 🌟 تجميع الأزرار في قائمة منسدلة للحفاظ على نظافة الجدول
                ActionsActionGroup::make([
                    ActionsEditAction::make(),

                    ActionsAction::make('manage_lessons')
                        ->label('إدارة الدروس')
                        ->icon('heroicon-o-film')
                        ->color('info')
                        ->url(fn ($record) => CourseResource::getUrl('lessons', ['record' => $record])),

                    ActionsAction::make('manage_quizzes')
                        ->label('إدارة الاختبارات')
                        ->icon('heroicon-o-academic-cap')
                        ->color('success')
                        ->url(fn ($record) => CourseResource::getUrl('quizzes', ['record' => $record])),

                    ActionsDeleteAction::make(),
                ])
                    ->label('الإجراءات')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('عرض خيارات الكورس'),
            ])
            ->toolbarActions([
                //
            ])
            ->groupedBulkActions([
                ActionsBulkActionGroup::make([
                    ActionsDeleteBulkAction::make(),
                ]),
            ]);
    }
}
