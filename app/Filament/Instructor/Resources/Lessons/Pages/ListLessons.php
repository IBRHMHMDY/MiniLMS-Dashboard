<?php

namespace App\Filament\Instructor\Resources\Lessons\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Resources\Lessons\LessonResource;
use App\Models\Course;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    // الحل السحري: إجبار Livewire على الاحتفاظ برقم الكورس في الذاكرة دائماً
    #[Url]
    public ?string $course_id = null;

    // تغيير عنوان الصفحة ديناميكياً ليعرض اسم الكورس
    public function getTitle(): string
    {
        if ($this->course_id) {
            $course = Course::find($this->course_id);
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
            // 'Lessons',
        ];
    }
    
    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();
        
        if ($this->course_id) {
            $query->where('course_id', $this->course_id);
        }
        
        return $query;
    }

    // تمرير رقم الكورس لزر إضافة درس جديد
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn () => $this->course_id 
                    ? $this->getResource()::getUrl('create', ['course_id' => $this->course_id]) 
                    : $this->getResource()::getUrl('create')
                ),
        ];
    }
}