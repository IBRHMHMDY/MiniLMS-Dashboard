<?php

namespace App\Filament\Instructor\Resources\CourseResource\Pages;

use App\Filament\Instructor\Resources\CourseResource;
use App\Filament\Instructor\Resources\Courses\CourseResource as CoursesCourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CoursesCourseResource::class;

    // توجيه المستخدم لصفحة الإدارة بعد إنشاء الكورس بدلاً من البقاء في صفحة الإنشاء
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // إزالة زر "Create & Create Another" لتنظيف واجهة المستخدم
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}