<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Models\Course;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
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
                
                // Approve Action
                Action::make('approve')
                    ->label(__('Approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Course $record) => $record->status === 'pending')
                    ->action(function (Course $record) {
                        $record->update([
                            'status' => 'approved',
                            'rejection_reason' => null,
                        ]);
                    }),

                // Reject Action with Modal
                Action::make('reject')
                    ->label(__('Reject'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Course $record) => $record->status === 'pending')
                    ->schema([
                        Textarea::make('rejection_reason')
                            ->label(__('Rejection Reason'))
                            ->required()
                            ->maxLength(1000)
                            ->helperText(__('Please provide a detailed reason so the instructor can fix the issues.')),
                    ])
                    ->action(function (array $data, Course $record): void {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                    }),
            ]);
    }
}
