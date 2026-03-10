<?php

namespace App\Filament\Instructor\Resources\Lessons\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Resources\Lessons\LessonResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditLesson extends EditRecord
{
    protected static string $resource = LessonResource::class;

    public function getBreadcrumbs(): array
    {
        $course = $this->record->course;

        return [
            CourseResource::getUrl('index') => 'My Courses',
            $course->title,
            LessonResource::getUrl('index', ['course_id' => $this->record->course_id]) => 'Lessons',
            'Edit Lesson',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save Changes & Go to Lessons'),

            Action::make('save_and_add_quiz')
                ->label('Save & Add Quiz')
                ->color('warning')
                ->icon('heroicon-o-document-text')
                ->action(function () {
                    $this->save();
                    $this->redirect('/instructor/quizzes/create?lesson_id=' . $this->record->id);
                }),

            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['course_id' => $this->record->course_id]);
    }
}