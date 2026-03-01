<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title', function (Builder $query) {
                        if (auth()->user()->hasRole('instructor')) {
                            $query->where('instructor_id', auth()->id());
                        }
                    })
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('content')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('video_url')
                    ->url()
                    ->default(null),
                TextInput::make('order_number')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
