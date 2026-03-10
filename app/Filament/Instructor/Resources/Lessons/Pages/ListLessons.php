<?php

namespace App\Filament\Instructor\Resources\Lessons\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Resources\Lessons\LessonResource;
use App\Models\Course;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    // تغيير عنوان الصفحة ديناميكياً ليعرض اسم الكورس
    public function getTitle(): string
    {
        $courseId = request()->query('course_id');
        if ($courseId) {
            $course = Course::find($courseId);
            return $course ? "Lessons: {$course->title}" : 'Manage Lessons';
        }
        return 'Manage Lessons';
    }

    // بناء الـ Breadcrumbs المعماري الهرمي
    public function getBreadcrumbs(): array
    {
        $courseId = request()->query('course_id');
        $course = $courseId ? Course::find($courseId) : null;

        return [
            CourseResource::getUrl('index') => 'My Courses',
            $course ? $course->title : 'Course',
            'Lessons',
        ];
    }

    // فلترة الجدول تلقائياً لعرض دروس الكورس المحدد فقط
    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();
        $courseId = request()->query('course_id');
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        return $query;
    }

    // تمرير رقم الكورس لزر إضافة درس جديد
    protected function getHeaderActions(): array
    {
        $courseId = request()->query('course_id');
        
        return [
            CreateAction::make()
                ->url(fn () => $courseId 
                    ? $this->getResource()::getUrl('create', ['course_id' => $courseId]) 
                    : $this->getResource()::getUrl('create')
                ),
        ];
    }
}