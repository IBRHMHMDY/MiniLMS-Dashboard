<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make(__('Course Details'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->disabled(),
                        Select::make('instructor_id')
                            ->label(__('Instructor'))
                            ->relationship('instructor', 'name')
                            ->disabled(),
                        Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name')
                            ->disabled(),
                        TextInput::make('price')
                            ->label(__('Price'))
                            ->numeric()
                            ->disabled(),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull()
                            ->disabled(),
                        Select::make('tags')
                            ->label(__('Tags'))
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
