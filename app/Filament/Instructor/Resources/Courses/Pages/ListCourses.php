<?php

namespace App\Filament\Instructor\Resources\Courses\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use App\Filament\Instructor\Widgets\InstructorStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;
    
    protected Width | string | null $maxContentWidth = Width::Full;
       
    public function getTitle(): string
    {
        return 'My Courses';
    }

    public function getBreadcrumbs(): array
    {

        return [
            'My Courses',
        ];
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New Course')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    // حقن ودجت الإحصائيات في رأس الصفحة
    protected function getHeaderWidgets(): array
    {
        return [
            
        ];
    }
}