<?php

namespace App\Filament\Instructor\Resources\CourseResource\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Widgets\InstructorStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add New Course')->icon('heroicon-o-plus-circle'),
        ];
    }

    // حقن ودجت الإحصائيات في رأس الصفحة
    protected function getHeaderWidgets(): array
    {
        return [
            InstructorStatsWidget::class,
        ];
    }
}