<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('instructor.name')
                    ->label(__('Instructor'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('EGP') // قم بتغيير العملة حسب متطلباتك
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'draft' => __('Draft'),
                        'pending' => __('Pending Approval'),
                        'approved' => __('Approved'),
                        'rejected' => __('Rejected'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                
                Action::make('approve')
                    ->label(__('Approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    // يظهر إذا كان الكورس معلقاً أو مرفوضاً مسبقاً
                    ->visible(fn (\App\Models\Course $record) => in_array($record->status, ['pending', 'rejected']))
                    ->action(function (\App\Models\Course $record) {
                        $record->update(['status' => 'approved', 'rejection_reason' => null]);
                    }),

                Action::make('reject')
                    ->label(__('Reject'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    // يظهر إذا كان معلقاً أو حتى معتمداً ونريد التراجع
                    ->visible(fn (\App\Models\Course $record) => in_array($record->status, ['pending', 'approved']))
                    ->form([
                        \Filament\Forms\Components\Textarea::make('rejection_reason')
                            ->label(__('Rejection Reason'))
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->action(function (array $data, \App\Models\Course $record): void {
                        $record->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);
                    }),

                // زر إضافي لإجبار الكورس على العودة لحالة الانتظار
                Action::make('mark_pending')
                    ->label(__('Mark Pending'))
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Course $record) => in_array($record->status, ['approved', 'rejected']))
                    ->action(function (\App\Models\Course $record) {
                        $record->update(['status' => 'pending']);
                    }),
            ]);
    }
}
