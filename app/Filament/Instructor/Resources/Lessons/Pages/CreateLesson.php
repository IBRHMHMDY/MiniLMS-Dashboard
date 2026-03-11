<?php

namespace App\Filament\Instructor\Resources\Lessons\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Resources\Lessons\LessonResource;
use App\Models\Course;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;

    public function getBreadcrumbs(): array
    {
        $courseId = request()->query('course_id') ?? ($this->data['course_id'] ?? null);
        
        $course = $courseId ? Course::find($courseId) : null;
        
        // 2. تأمين قراءة العنوان لمنع خطأ Null Property
        $courseTitle = $course ? $course->title : 'Course';

        return [
            CourseResource::getUrl('index') => 'My Courses',
            $courseTitle,
            LessonResource::getUrl('index', ['course_id' => $courseId]) => 'Lessons',
            'Add New Lesson',
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Save & Go to Lessons'),

            Action::make('save_and_add_another')
                ->label('Save & Add Another Lesson')
                ->color('info')
                ->icon('heroicon-o-plus-circle')
                ->action(function () {
                    $this->create();
                    // 3. الحل المعماري الأقوى: جلب رقم الكورس من الدرس الذي تم حفظه للتو في قاعدة البيانات!
                    $courseId = $this->record->course_id;
                    
                    $this->redirect($this->getResource()::getUrl('create', ['course_id' => $courseId]));
                }),

            Action::make('save_and_add_quiz')
                ->label('Save & Add Quiz')
                ->color('warning')
                ->icon('heroicon-o-document-text')
                ->action(function () {
                    $this->create();
                    $this->redirect('/instructor/quizzes/create?lesson_id=' . $this->record->id);
                }),

            $this->getCancelFormAction(),
        ];
    }

    // التوجيه المعماري: العودة لقائمة دروس هذا الكورس تحديداً
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['course_id' => $this->record->course_id]);
    }
}