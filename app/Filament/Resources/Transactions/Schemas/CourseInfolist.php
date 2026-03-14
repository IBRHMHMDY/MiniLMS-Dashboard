<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Course Details'))
                    ->schema([
                        ImageEntry::make('image_path')->label(__('Cover Image')),
                        TextEntry::make('title')->label(__('Title'))->weight('bold')->size('lg'),
                        TextEntry::make('instructor.name')->label(__('Instructor'))->badge()->color('gray'),
                        TextEntry::make('category.name')->label(__('Category'))->badge()->color('info'),
                        TextEntry::make('price')->label(__('Price'))->money('EGP'),
                        TextEntry::make('status')->label(__('Status'))->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            }),
                        TextEntry::make('description')->label(__('Description'))->columnSpanFull()->prose(),
                    ])->columns(2),
            ]);
    }
}