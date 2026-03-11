<?php

namespace App\Filament\Instructor\Resources\FinalExams\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Resources\FinalExams\FinalExamResource;
use App\Models\Course;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListFinalExams extends ListRecords
{
    protected static string $resource = FinalExamResource::class;

    // حفظ رقم الكورس بالذاكرة لضمان عدم ضياعه أثناء عمليات الحفظ
    #[Url]
    public ?string $course_id = null;

    public function getTitle(): string
    {
        if ($this->course_id) {
            $course = Course::find($this->course_id);
            return $course ? "Final Exams: {$course->title}" : 'Manage Final Exams';
        }
        return 'Manage Final Exams';
    }

    public function getBreadcrumbs(): array
    {
        $course = $this->course_id ? Course::find($this->course_id) : null;

        return [
            CourseResource::getUrl('index') => 'My Courses',
            url()->current() . "?course_id={$this->course_id}" => $course ? $course->title : 'Course Final Exam',
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

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Final Exam')
                ->icon('heroicon-o-plus-circle')
                ->modalWidth('full')
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['course_id'] = $this->course_id;
                    $data['is_final_exam'] = true;
                    return $data;
                }),
        ];
    }
}