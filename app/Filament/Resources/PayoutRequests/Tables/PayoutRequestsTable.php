<?php

namespace App\Filament\Resources\PayoutRequests\Tables;

use App\Models\PayoutRequest;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PayoutRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('instructor.name')
                    ->label(__('Instructor'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('Requested Amount'))
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('paid_at')
                    ->label(__('Paid At'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder(__('Not Paid Yet')),
                TextColumn::make('created_at')
                    ->label(__('Requested At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('Pending'),
                        'paid' => __('Paid'),
                        'rejected' => __('Rejected'),
                    ]),
            ])
            ->recordActions([
                Action::make('mark_as_paid')
                    ->label(__('Mark as Paid'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(__('Confirm Payment'))
                    ->modalDescription(__('Are you sure you have transferred the funds to the instructor? This action will mark the request as paid.'))
                    ->visible(fn (PayoutRequest $record) => $record->status === 'pending')
                    ->action(function (PayoutRequest $record) {
                        $record->update([
                            'status' => 'paid',
                            'paid_at' => Carbon::now(),
                        ]);
                    }),

                // زر الرفض
                Action::make('reject')
                    ->label(__('Reject'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (PayoutRequest $record) => $record->status === 'pending')
                    ->action(function (PayoutRequest $record) {
                        $record->update([
                            'status' => 'rejected',
                        ]);
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
