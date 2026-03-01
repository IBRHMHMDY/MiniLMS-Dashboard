<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('instructor_id')
                    ->relationship('instructor', 'name', fn (Builder $query) => $query->role('instructor'))
                    ->default(auth()->id())
                    ->disabled(fn () => auth()->user()->hasRole('instructor'))
                    ->dehydrated()
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('LE'),
                FileUpload::make('image_path')
                    ->image()
                    ->directory('courses'),
            ]);
    }
}
