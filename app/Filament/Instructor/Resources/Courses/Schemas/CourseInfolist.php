<?php

namespace App\Filament\Instructor\Resources\Courses\Schemas;

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
                Section::make('Course Details')
                    ->schema([
                        ImageEntry::make('image_path')->label('Cover Image')->hiddenLabel(),
                        TextEntry::make('title')->weight('bold')->size('lg'),
                        TextEntry::make('category.name')->badge()->color('info'),
                        TextEntry::make('price')->money('usd')->label('Price'),
                        TextEntry::make('description')->prose()->columnSpanFull(),
                    ])->columnSpanFull(3),
            ]);
    }
}
