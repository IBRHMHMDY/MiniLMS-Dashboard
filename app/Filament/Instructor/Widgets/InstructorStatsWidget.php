<?php

namespace App\Filament\Instructor\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
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

        // استعلامات جلب البيانات
        $totalCourses = Course::where('instructor_id', $instructorId)->count();
        $publishedCourses = Course::where('instructor_id', $instructorId)->where('is_published', true)->count();
        $draftCourses = Course::where('instructor_id', $instructorId)->where('is_published', false)->count();
        
        $freeCourses = Course::where('instructor_id', $instructorId)->where(function ($query) {
            $query->whereNull('price')->orWhere('price', 0);
        })->count();
        
        $paidCourses = Course::where('instructor_id', $instructorId)->where('price', '>', 0)->count();
        
        $totalStudents = Enrollment::whereHas('course', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->count();

        // 2. تعريف التأثيرات الحركية (Tailwind CSS) ليتم تطبيقها على كل البطاقات
        // Hover: ترتفع البطاقة للأعلى قليلاً، ويزيد الظل بشكل ناعم
        $hoverEffect = 'hover:-translate-y-2 hover:shadow-xl transition-transform transition-shadow duration-300 cursor-default';

        return [
            Stat::make('Total Courses', $totalCourses)
                ->description('All your courses')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->icon('heroicon-o-rectangle-stack') // أيقونة كبيرة
                ->chart([1, 3, 2, 5, 4, 6, 8]) // رسم بياني جمالي (Sparkline)
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Published', $publishedCourses)
                ->description('Live & active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success') // لون أخضر
                ->icon('heroicon-o-check-badge')
                ->chart([0, 2, 4, 6, 8, 10])
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Drafts', $draftCourses)
                ->description('Work in progress')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning') // لون برتقالي
                ->icon('heroicon-o-document-text')
                ->chart([5, 4, 3, 3, 2, 1])
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Free Courses', $freeCourses)
                ->description('No cost')
                ->descriptionIcon('heroicon-m-gift')
                ->color('info') // لون أزرق فاتح
                ->icon('heroicon-o-gift')
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Paid Courses', $paidCourses)
                ->description('Generating revenue')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger') // لون أحمر
                ->icon('heroicon-o-banknotes')
                ->chart([1, 2, 5, 8, 15, 20])
                ->extraAttributes(['class' => $hoverEffect]),

            Stat::make('Total Students', $totalStudents)
                ->description('Enrolled learners')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray') // لون رمادي أنيق
                ->icon('heroicon-o-users')
                ->chart([10, 20, 35, 50, 80, 120])
                ->extraAttributes(['class' => $hoverEffect]),
        ];
    }
}