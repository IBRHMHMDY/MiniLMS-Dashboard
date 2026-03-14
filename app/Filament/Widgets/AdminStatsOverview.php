<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Course;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Transaction::sum('platform_commission');
        $studentsCount = User::role('Student')->count();
        $pendingCourses = Course::where('status', 'pending')->count();

        return [
            Stat::make(__('Total Platform Revenue'), '$' . number_format($totalRevenue, 2))
                ->description(__('All time platform commissions'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // رسم بياني مصغر للزينة
            
            Stat::make(__('Registered Students'), $studentsCount)
                ->description(__('Total active learners in the system'))
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make(__('Pending Courses'), $pendingCourses)
                ->description(__('Courses requiring your review'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}