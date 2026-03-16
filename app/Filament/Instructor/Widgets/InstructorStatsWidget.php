<?php

namespace App\Filament\Instructor\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class InstructorStatsWidget extends BaseWidget
{
    // تحديد ترتيب عرض الويدجت في لوحة التحكم
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $instructorId = Auth::id();

        // 1. إجمالي الكورسات الخاصة بالمدرب
        $totalCourses = Course::where('instructor_id', $instructorId)->count();

        // 2. إجمالي الطلاب المشتركين في كورساته
        $totalStudents = Enrollment::whereHas('course', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->count();

        // 3. إجمالي الأرباح (من المعاملات المكتملة لكورساته)
        $totalRevenue = Transaction::whereHas('course', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->where('status', 'completed') // افتراض أن 'completed' هي الحالة الناجحة
        ->sum('amount');

        return [
            Stat::make(__('Total Courses'), $totalCourses)
                ->icon('heroicon-o-academic-cap')
                ->description(__('Your published & draft courses'))
                ->color('primary'),

            Stat::make(__('Total Enrollments'), $totalStudents)
                ->icon('heroicon-o-users')
                ->description(__('Students enrolled in your courses'))
                ->color('success'),

            Stat::make(__('Total Revenue'), '$' . number_format($totalRevenue, 2))
                ->icon('heroicon-o-currency-dollar')
                ->description(__('Total earnings from your courses'))
                ->color('warning'),
        ];
    }
}