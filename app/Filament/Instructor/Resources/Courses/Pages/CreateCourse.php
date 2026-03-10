<?php

namespace App\Filament\Instructor\Resources\CourseResource\Pages;


use App\Filament\Instructor\Resources\Courses\CourseResource as NewInstructorCourses;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = NewInstructorCourses::class;

    // تخصيص مسار التنقل (Breadcrumbs)
    public function getBreadcrumbs(): array
    {
        return [
            url('/instructor') => 'Dashboard',
            $this->getResource()::getUrl('index') => 'My Courses',
            'Add New Course',
        ];
    }

    // إعداد الأزرار المخصصة حسب المتطلبات (UX)
    protected function getFormActions(): array
    {
        return [
            // زر 1: الحفظ والانتقال للصفحة الرئيسية (تعديل الزر الافتراضي)
            $this->getCreateFormAction()
                ->label('Save & Go to Dashboard'),

            // زر 2: الحفظ والانتقال لصفحة إضافة درس جديد
            Action::make('save_and_add_lesson')
                ->label('Save & Add Lesson')
                ->color('success')
                ->icon('heroicon-o-plus-circle')
                ->action(function () {
                    $this->create();
                    // التوجيه لمسار إنشاء الدرس (سنبنيه في الخطوة القادمة) مع تمرير ID الكورس
                    $this->redirect('/instructor/lessons/create?course_id=' . $this->record->id);
                }),

            // زر 3: الإلغاء
            $this->getCancelFormAction(),
        ];
    }

    // التوجيه الافتراضي للزر الأول
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}