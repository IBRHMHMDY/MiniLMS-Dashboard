<?php

namespace App\Filament\Instructor\Resources\Courses\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ManageCourseCurriculum extends ManageRelatedRecords
{
    protected static string $resource = CourseResource::class;

    protected static string $relationship = 'sections';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    public function getTitle(): string
    {
        return __('Curriculum');
    }

    public function getBreadcrumbs(): array
    {
        $course = $this->getOwnerRecord();
        
        return [
            // 2. مسار العودة لقائمة الكورسات
            $this->getResource()::getUrl('index') => __('My Courses'),
            
            // 3. مسار العودة لصفحة إعدادات الكورس (الـ Key هو الرابط، والـ Value هو اسم الكورس)
            '' => $course->title ?? __('Course'),

        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->reorderable('sort_order') // تفعيل ترتيب الأقسام بالسحب والإفلات
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label(__('Section Title'))
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('lessons.title')
                    ->label(__('Lessons Inside'))
                    ->badge()
                    ->color('info')
                    ->separator(',') // عرض الدروس بجوار بعضها
                    ->limitList(10), // تجنب ازدحام الشاشة إذا كان هناك دروس كثيرة

                ToggleColumn::make('is_published')
                    ->label(__('Published')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Add Section'))
                    ->icon('heroicon-o-plus')
                    ->modalWidth('lg')
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Section Title'))
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_published')
                            ->label(__('Published'))
                            ->default(true),
                    ]),
            ])
            ->recordActions([
                EditAction::make('manage_lessons')
                    ->label(__('Manage Lessons'))
                    ->icon('heroicon-o-film')
                    ->color('primary')
                    ->modalHeading(fn ($record) => __('Lessons for: ') . $record->title)
                    ->modalDescription(__('Add, edit, and reorder lessons inside this section.'))
                    ->modalWidth('5xl')
                    ->modalSubmitActionLabel(__('Save Lessons'))
                    ->schema([
                        Repeater::make('lessons')
                            ->relationship('lessons') // ربط الدروس بالقسم الحالي
                            ->label('')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('title')
                                        ->label(__('Lesson Title'))
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state . '-' . uniqid()))),

                                    TextInput::make('slug')
                                        ->label(__('URL Slug'))
                                        ->required()
                                        ->readOnly(),
                                ]),

                                Grid::make(3)->schema([
                                    TextInput::make('video_url')
                                        ->label(__('Video URL'))
                                        ->url()
                                        ->columnSpan(2),

                                    TextInput::make('duration_in_minutes')
                                        ->label(__('Duration (Mins)'))
                                        ->numeric()
                                        ->default(0)
                                        ->required()
                                        ->columnSpan(1),
                                ]),

                                Grid::make(2)->schema([
                                    Toggle::make('is_free_preview')
                                        ->label(__('Free Preview'))
                                        ->default(false),

                                    Toggle::make('is_published')
                                        ->label(__('Published'))
                                        ->default(true),
                                ]),
                                Grid::make(1)->schema([
                                    FileUpload::make('attachments')
                                        ->label(__('Lesson Attachments (PDF, ZIP, etc.)'))
                                        ->directory('lesson-attachments')
                                        ->multiple() // السماح برفع ملفات متعددة دفعة واحدة
                                        ->downloadable()
                                        ->preserveFilenames()
                                        ->maxSize(20480) // الحد الأقصى 20 ميجابايت للملف
                                    ])->columnSpanFull(),
                                Grid::make(1)->schema([
                                    RichEditor::make('content')
                                        ->label(__('Lesson Content'))
                                        ->toolbarButtons([
                                            'bold', 'italic', 'strike', 'link', 'h2', 'h3', 'bulletList', 'orderedList', 'redo', 'undo',
                                    ]),
                                ]),
                            ])
                            ->orderColumn('sort_order') // تفعيل ترتيب الدروس بالسحب والإفلات داخل الـ Modal
                            ->defaultItems(0)
                            ->collapsible()
                            ->collapsed()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel(__('Add Lesson')),
                    ]),

                // زر "تعديل القسم": لتعديل اسم القسم وحالته
                EditAction::make('edit_section')
                    ->label(__('Edit Section'))
                    ->icon('heroicon-o-pencil')
                    ->color('gray')
                    ->modalWidth('lg')
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Section Title'))
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_published')
                            ->label(__('Published')),
                    ]),

                DeleteAction::make(),
            ]);
    }


    // public function form(Schema $form): Schema
    // {
    //     return $form
    //         ->schema([
    //             Section::make(__('Course Sections & Lessons'))
    //                 ->description(__('Build your course structure by adding sections and lessons using drag and drop.'))
    //                 ->schema([
    //                     // 1. Repeater للأقسام
    //                     Repeater::make('sections')
    //                         ->relationship()
    //                         ->label('')
    //                         ->schema([
    //                             Grid::make(2)->schema([
    //                                 TextInput::make('title')
    //                                     ->label(__('Section Title'))
    //                                     ->required()
    //                                     ->columnSpan(1),
                                        
    //                                 Toggle::make('is_published')
    //                                     ->label(__('Published'))
    //                                     ->default(true)
    //                                     ->inline(false)
    //                                     ->columnSpan(1),
    //                             ]),

    //                             // 2. Nested Repeater للدروس داخل كل قسم
    //                             Repeater::make('lessons')
    //                                 ->relationship()
    //                                 ->label(__('Lessons'))
    //                                 ->schema([
    //                                     Grid::make(2)->schema([
    //                                         TextInput::make('title')
    //                                             ->label(__('Lesson Title'))
    //                                             ->required()
    //                                             ->live(onBlur: true)
    //                                             ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state . '-' . uniqid()))),

    //                                         TextInput::make('slug')
    //                                             ->label(__('URL Slug'))
    //                                             ->required()
    //                                             ->readOnly(),
    //                                     ]),

    //                                     Grid::make(3)->schema([
    //                                         TextInput::make('video_url')
    //                                             ->label(__('Video URL'))
    //                                             ->url()
    //                                             ->columnSpan(2),

    //                                         TextInput::make('duration_in_minutes')
    //                                             ->label(__('Duration (Mins)'))
    //                                             ->numeric()
    //                                             ->default(0)
    //                                             ->required()
    //                                             ->columnSpan(1),
    //                                     ]),

    //                                     Grid::make(2)->schema([
    //                                         Toggle::make('is_free_preview')
    //                                             ->label(__('Free Preview'))
    //                                             ->default(false),

    //                                         Toggle::make('is_published')
    //                                             ->label(__('Published'))
    //                                             ->default(true),
    //                                     ]),
    //                                     Grid::make(1)->schema([
    //                                         FileUpload::make('attachments')
    //                                             ->label(__('Lesson Attachments (PDF, ZIP, etc.)'))
    //                                             ->directory('lesson-attachments')
    //                                             ->multiple() // السماح برفع ملفات متعددة دفعة واحدة
    //                                             ->downloadable()
    //                                             ->preserveFilenames()
    //                                             ->maxSize(20480) // الحد الأقصى 20 ميجابايت للملف
    //                                         ])->columnSpanFull(),
    //                                     Grid::make(1)->schema([
    //                                         RichEditor::make('content')
    //                                             ->label(__('Lesson Content'))
    //                                             ->toolbarButtons([
    //                                                 'bold', 'italic', 'strike', 'link', 'h2', 'h3', 'bulletList', 'orderedList', 'redo', 'undo',
    //                                         ]),
    //                                     ]),
    //                                 ])
    //                                 ->orderColumn('sort_order') // تفعيل الـ Drag & Drop للدروس
    //                                 ->defaultItems(0)
    //                                 ->collapsible()
    //                                 ->cloneable()
    //                                 ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
    //                                 ->addActionLabel(__('Add Lesson')),

                                    
    //                         ])
    //                         ->orderColumn('sort_order') // تفعيل الـ Drag & Drop للأقسام
    //                         ->defaultItems(0)
    //                         ->collapsible()
    //                         ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
    //                         ->addActionLabel(__('Add Section'))
    //                 ])
    //                 ->columnSpanFull(),
    //         ]);
    // }

    // تغيير اسم زر الحفظ ليناسب الصفحة
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label(__('Save Curriculum')),
            $this->getCancelFormAction(),
        ];
    }
}