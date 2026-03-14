<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopInstructorsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full'; // لجعل الجدول يأخذ العرض بالكامل

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::role(['Instructor', 'instructor'])
                    ->withSum('transactionsAsInstructor as total_revenue', 'amount')
                    ->orderByDesc('total_revenue')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Instructor Name'))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label(__('Verified'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label(__('Total Generated Revenue'))
                    ->money('USD')
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->heading(__('Top 5 Instructors (By Revenue)'))
            ->paginated(false); // إيقاف الترقيم لأننا نعرض 5 فقط
    }
}