<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('User Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_verified')
                    ->label('isVerified?')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('danger'),
                TextColumn::make('banned_at')
                    ->label('Baned')
                    ->formatStateUsing(fn ($state) => $state ? 'Baned' : 'Active')
                    ->badge()
                    ->color(fn ($state) => $state ? 'danger' : 'success'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                
               
                Action::make(__('verify'))
                    ->label('Verified')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->hasRole('Instructor') && !$record->is_verified)
                    ->action(fn (User $record) => $record->update(['is_verified' => true])),

                Action::make(__('unverify'))
                    ->label('unVerified')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->hasRole('Instructor') && $record->is_verified)
                    ->action(fn (User $record) => $record->update(['is_verified' => false])),

                // ميزة حظر المستخدم
                Action::make('ban')
                    ->label(__('Ban'))
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Ban User Confirmed')
                    ->modalDescription('Are You sure Ban This User? can not login to This System.')
                    ->visible(fn (User $record) => is_null($record->banned_at) && !$record->hasRole('Super Admin'))
                    ->action(fn (User $record) => $record->update(['banned_at' => Carbon::now()])),

                Action::make(__('unban'))
                    ->label('unBan')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => !is_null($record->banned_at))
                    ->action(fn (User $record) => $record->update(['banned_at' => null])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
