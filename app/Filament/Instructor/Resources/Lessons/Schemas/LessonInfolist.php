<?php

namespace App\Filament\Instructor\Resources\Lessons\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LessonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lesson Details')
                    ->schema([
                        TextEntry::make('course.title')->label('Course')->badge()->color('info'),
                        TextEntry::make('title')->label('Lesson Name')->weight('bold')->size('lg'),
                        TextEntry::make('video_url')->label('Video Link')->url(fn ($record) => $record->video_url)->openUrlInNewTab()->color('primary'),
                        TextEntry::make('is_published')->label('Status')->badge()->color(fn (string $state): string => match ($state) { '1' => 'success', '0' => 'danger', default => 'gray' })->formatStateUsing(fn ($state) => $state ? 'Published' : 'Draft'),
                        TextEntry::make('content')->label('Content')->prose()->columnSpanFull(),
                    ])->columnSpanFull(3),
            ]);
    }
}
