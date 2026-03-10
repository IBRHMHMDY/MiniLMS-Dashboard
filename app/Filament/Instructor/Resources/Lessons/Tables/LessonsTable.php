<?php

namespace App\Filament\Instructor\Resources\Lessons\Tables;

use App\Filament\Instructor\Resources\Lessons\LessonResource;
use App\Models\Lesson;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LessonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Lesson Name')
                    ->description(fn (Lesson $record): string => $record->course?->title ?? 'No Course')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                
                    IconColumn::make('quizzes_count')
                    ->label('Has Quiz?')
                    ->boolean()
                    ->getStateUsing(fn (Lesson $record): bool => $record->quizzes_count > 0)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('video_url')
                    ->label('Video Link')
                    ->copyable()
                    ->copyMessage('Video link copied!')
                    ->limit(30)
                    ->color('primary'),

                ToggleColumn::make('is_published')
                    ->label('Published')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                SelectFilter::make('course_id')
                    ->label('Filter by Course')
                    ->relationship('course', 'title', function (Builder $query) {
                        return $query->where('instructor_id', Auth::id());
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View Lesson')
                    ->color('gray')
                    ->modalHeading('Lesson Details')
                    ->modalSubmitAction(false) // إخفاء زر التأكيد الافتراضي
                    ->modalCancelAction(fn ($action) => $action->label('Close'))
                    ->extraModalFooterActions([
                        Action::make('edit_lesson')
                            ->label('Edit Lesson')
                            ->color('primary')
                            ->url(fn (Lesson $record): string => LessonResource::getUrl('edit', ['record' => $record])),

                        DeleteAction::make('delete_lesson')
                            ->label('Delete Lesson')
                            ->color('danger')
                            ->requiresConfirmation(), // رسالة تأكيد الحذف
                    ]),

                // زر إدارة اختبارات الدرس (يحول للصفحة مع فلترة برقم الدرس)
                Action::make('lesson_quiz')
                    ->label('َLesson Quiz')
                    ->icon('heroicon-o-document-text')
                    ->color('warning')
                    ->url(fn (Lesson $record): string => '/instructor/quizzes?tableFilters[lesson_id][value]='.$record->id),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
