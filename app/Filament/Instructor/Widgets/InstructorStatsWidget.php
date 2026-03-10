<?php

namespace App\Filament\Instructor\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class InstructorStatsWidget extends BaseWidget
{
    // تسريع التحديث وجعله يمتد بعرض الشاشة
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $instructorId = Auth::id();

        // سحب كورسات المدرب فقط (Data Isolation)
        $courses = Course::where('instructor_id', $instructorId)->get();

        $totalCourses = $courses->count();
        $publishedCourses = $courses->where('is_published', true)->count();
        $unpublishedCourses = $courses->where('is_published', false)->count();
        $freeCourses = $courses->where('is_free', true)->count();
        $paidCourses = $courses->where('is_free', false)->count();

        // حساب عدد الطلاب المشتركين بجميع كورسات هذا المدرب (بدون تكرار)
        $totalStudents = Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');

        return [
            Stat::make('Total Courses', $totalCourses)
                ->icon('heroicon-o-academic-cap')
                ->color('primary'),
            Stat::make('Published', $publishedCourses)
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Unpublished', $unpublishedCourses)
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
            Stat::make('Free Courses', $freeCourses)
                ->icon('heroicon-o-gift')
                ->color('success'),
            Stat::make('Paid Courses', $paidCourses)
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),
            Stat::make('Total Students', $totalStudents)
                ->icon('heroicon-o-users')
                ->color('info'),
        ];
    }
}