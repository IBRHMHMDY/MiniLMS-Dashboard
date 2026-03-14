<?php

namespace App\Filament\Instructor\Resources\Courses\Schemas;

use App\Enums\CourseLevel;
use App\Enums\CourseStatus;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): schema
    {
        return $schema->components([
            Hidden::make('instructor_id')
                ->default(Auth::id()),

            Tabs::make('Course Details')
                ->tabs([
                    // Tab 1: Basic Information
                    Tab::make(__('Basic Info'))
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('Course Title'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->label(__('URL Slug'))
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Select::make('category_id')
                                    ->label(__('Category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('language')
                                    ->label(__('Language'))
                                    ->options([
                                        'ar' => __('Arabic'),
                                        'en' => __('English'),
                                    ])
                                    ->default('ar')
                                    ->required(),

                                Select::make('level')
                                    ->label(__('Difficulty Level'))
                                    ->options(CourseLevel::class)
                                    ->default(CourseLevel::BEGINNER)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                        ]),

                    // Tab 2: Course Content
                    Tabs\Tab::make(__('Content'))
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Textarea::make('short_description')
                                ->label(__('Short Description'))
                                ->maxLength(255)
                                ->columnSpanFull(),

                            RichEditor::make('description')
                                ->label(__('Full Description'))
                                ->toolbarButtons([
                                    'bold', 'italic', 'underline', 'h2', 'h3', 'bulletList', 'orderedList', 'link',
                                ])
                                ->columnSpanFull(),
                        ]),

                    // Tab 3: Media
                    Tabs\Tab::make(__('Media'))
                        ->icon('heroicon-o-video-camera')
                        ->schema([
                            FileUpload::make('thumbnail')
                                ->label(__('Course Thumbnail'))
                                ->image()
                                ->directory('courses/thumbnails')
                                ->maxSize(2048)
                                ->columnSpanFull(),

                            TextInput::make('intro_video_url')
                                ->label(__('Intro Video URL (YouTube/Vimeo)'))
                                ->url()
                                ->columnSpanFull(),
                        ]),

                    // Tab 4: Pricing & Publishing
                    Tabs\Tab::make(__('Pricing & Status'))
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            Grid::make(2)->schema([
                                Toggle::make('is_free')
                                    ->label(__('Is this course free?'))
                                    ->live()
                                    ->default(false),

                                TextInput::make('price')
                                    ->label(__('Price'))
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0.00)
                                    ->disabled(fn (Get $get) => $get('is_free')),
                            ]),

                            Grid::make(2)->schema([
                                Select::make('status')
                                    ->label(__('Publishing Status'))
                                    ->options(CourseStatus::class)
                                    ->default(CourseStatus::DRAFT)
                                    ->required(),

                                DateTimePicker::make('published_at')
                                    ->label(__('Publish Date'))
                                    ->nullable(),
                            ]),
                        ]),
                ])
                ->columnSpanFull()
        ]);
    }
}