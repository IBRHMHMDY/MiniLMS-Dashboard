<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
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
