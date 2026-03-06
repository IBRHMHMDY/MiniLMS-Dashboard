<?php

namespace App\Filament\Instructor\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('instructor_id')
                    ->default(fn () => auth()->id()),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_free')
                    ->label('كورس مجاني؟')
                    ->default(true)
                    ->live(), // Live يجعل الواجهة تتفاعل فوراً عند التغيير
                TextInput::make('price')
                    ->label('السعر (EGP)')
                    ->numeric()
                    ->prefix('EGP')
                    ->hidden(fn (Get $get) => $get('is_free')) // يختفي إذا كان مجانياً
                    ->required(fn (Get $get) => ! $get('is_free')), // يصبح إجبارياً إذا لم يكن مجانياً
                FileUpload::make('image_path')
                    ->image()
                    ->directory('courses'),
            ]);
    }
}
