<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BestSellingCoursesWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Course::where('status', 'approved')
                    ->withCount('enrollments')
                    ->orderByDesc('enrollments_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Course Title'))
                    ->weight('bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('instructor.name')
                    ->label(__('Instructor')),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('EGP')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label(__('Total Enrollments'))
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-users'),
            ])
            ->heading(__('Top 5 Best Selling Courses'))
            ->paginated(false);
    }
}