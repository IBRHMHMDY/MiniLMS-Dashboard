<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class MonthlyRevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return __('Monthly Revenue (Platform Commission) - ' . Carbon::now()->year);
    }

    protected function getData(): array
    {
        $data = [];
        $months = [];

        $currentYear = Carbon::now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::create()->month($month)->format('M');
            
            $monthlySum = Transaction::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('platform_commission');
                
            $data[] = (float) $monthlySum;
        }

        return [
            'datasets' => [
                [
                    'label' => __('Revenue (EGP)'),
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#10b981', // لون أخضر معبر عن الأرباح
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}