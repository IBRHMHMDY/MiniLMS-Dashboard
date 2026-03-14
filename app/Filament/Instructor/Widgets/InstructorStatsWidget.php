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
    // 1. جعل البطاقات تظهر على سطر واحد في الشاشات الكبيرة (6 أعمدة)
    protected function getColumns(): array|int|null
    {
        return [
            'default' => 2, // عمودين في الموبايل
            'sm' => 3,      // 3 أعمدة في التابلت
            'xl' => 6,      // 6 أعمدة (سطر واحد) في الشاشات الكبيرة
        ];
    }

    protected function getStats(): array
    {
        $instructorId = Auth::id();

        // 2. استعلامات جلب البيانات (محدثة لتتوافق مع نظام الـ Status الجديد)
        $totalCourses = Course::where('instructor_id', $instructorId)->count();
        $approvedCourses = Course::where('instructor_id', $instructorId)->where('status', 'approved')->count();
        $pendingCourses = Course::where('instructor_id', $instructorId)->where('status', 'pending')->count();
        $draftAndRejected = Course::where('instructor_id', $instructorId)->whereIn('status', ['draft', 'rejected'])->count();
        
        $totalStudents = Enrollment::whereHas('course', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->count();

        // جلب إجمالي أرباح المدرب الصافية
        $totalEarnings = Transaction::where('instructor_id', $instructorId)->sum('instructor_commission');

        // 3. تعريف التأثيرات الحركية (Tailwind CSS)
        $hoverEffect = 'hover:-translate-y-2 hover:shadow-xl transition-transform transition-shadow duration-300 cursor-default';

        return [
            Stat::make('Total Courses', $totalCourses)
                ->description('All your courses')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->icon('heroicon-o-rectangle-stack')
                ->chart([1, 3, 2, 5, 4, 6, 8])
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Approved (Live)', $approvedCourses)
                ->description('Published to students')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success') 
                ->icon('heroicon-o-check-badge')
                ->chart([0, 2, 4, 6, 8, 10])
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Pending Review', $pendingCourses)
                ->description('Waiting for admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info') 
                ->icon('heroicon-o-clock')
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Drafts & Rejected', $draftAndRejected)
                ->description('Needs your attention')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning') 
                ->icon('heroicon-o-document-text')
                ->chart([5, 4, 3, 3, 2, 1])
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Total Students', $totalStudents)
                ->description('Enrolled learners')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray') 
                ->icon('heroicon-o-users')
                ->chart([10, 20, 35, 50, 80, 120])
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Total Earnings', '$' . number_format($totalEarnings, 2))
                ->description('Your net commissions')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success') 
                ->icon('heroicon-o-banknotes')
                ->chart([1, 2, 5, 8, 15, 20])
                ->extraAttributes(['class' => $hoverEffect]),
        ];
    }
}