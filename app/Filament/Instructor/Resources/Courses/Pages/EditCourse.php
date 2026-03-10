<?php

namespace App\Filament\Instructor\Resources\CourseResource\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource as EditInstructorCourses;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = EditInstructorCourses::class;

    public function getBreadcrumbs(): array
    {
        return [
            url('/instructor/courses') => 'Dashboard',
            $this->getResource()::getUrl('index') => 'My Courses',
            'Edit Course',
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
            $this->getSaveFormAction()
                ->label('Save Changes & Go to Dashboard'),

            Action::make('save_and_add_lesson')
                ->label('Save & Add Lesson')
                ->color('success')
                ->icon('heroicon-o-plus-circle')
                ->action(function () {
                    $this->save();
                    $this->redirect('/instructor/lessons/create?course_id=' . $this->record->id);
                }),


        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}