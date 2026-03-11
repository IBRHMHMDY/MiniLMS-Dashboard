<?php

namespace App\Filament\Instructor\Resources\Quizzes\Pages;

use App\Filament\Instructor\Resources\Quizzes\QuizResource;
use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Resources\Lessons\LessonResource;
use App\Models\Lesson;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url; // استدعاء هام جداً

class ListQuizzes extends ListRecords
{
    protected static string $resource = QuizResource::class;

    // الحل السحري: إجبار Livewire على الاحتفاظ برقم الدرس في الذاكرة والرابط دائماً
    #[Url]
    public ?string $lesson_id = null;

    public function getTitle(): string
    {
        if ($this->lesson_id) {
            $lesson = Lesson::find($this->lesson_id);
            return $lesson ? "Quizzes for: {$lesson->title}" : 'Manage Quizzes';
        }
        return 'Manage Quizzes';
    }

    public function getBreadcrumbs(): array
    {
        $lesson = $this->lesson_id ? Lesson::with('course')->find($this->lesson_id) : null;

        return [
            CourseResource::getUrl('index') => 'My Courses',
            LessonResource::getUrl('index', ['course_id' => $lesson?->course_id]) => ($lesson?->course?->title ?? 'Course'),
            // $lesson?->title ?? 'Lesson Quizzes',
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();
        
        if ($this->lesson_id) {
            $query->where('lesson_id', $this->lesson_id);
        }
        
        return $query;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Quiz')
                ->icon('heroicon-o-plus-circle')
                ->modalWidth('7xl')
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['lesson_id'] = $this->lesson_id;
                    return $data;
                }),
        ];
    }
}