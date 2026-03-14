<?php

namespace App\Filament\Instructor\Resources\Lessons\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;


class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lesson Information')
                    ->schema([
                        // السطر الأول: الكورس (يتم التقاطه من الرابط تلقائياً) - عنوان الدرس
                        Grid::make(3)->schema([
                            
                            TextInput::make('title')
                                ->label('Lesson Title')
                                ->required()
                                ->maxLength(255),
                            Toggle::make('is_free_preview')
                                ->label('Free Preview')
                                ->helperText('Allow non-enrolled students to watch this lesson.')
                                ->inline(false)
                                ->onColor('success'),
                            Toggle::make('is_published')
                                ->label('Publish Lesson')
                                ->default(false)
                                ->inline(false),
                        ]),


                        // السطر الثاني: رابط الفيديو - حالة النشر
                        Grid::make(2)->schema([
                            TextInput::make('video_url')
                                ->label('Video Link')
                                ->url()
                                ->placeholder('https://...')
                                ->required(),
                            TextInput::make('duration_in_minutes')
                                ->label('Duration (Minutes)')
                                ->numeric()
                                ->minValue(1)
                                ->suffix('Mins'),
                        ]),
                        Grid::make(3)->schema([
                            FileUpload::make('attachments')
                                ->label('Lesson Attachments (PDF, ZIP, etc.)')
                                ->directory('lesson-attachments')
                                ->multiple() // السماح برفع ملفات متعددة دفعة واحدة
                                ->downloadable()
                                ->preserveFilenames()
                                ->maxSize(20480) // الحد الأقصى 20 ميجابايت للملف
                                ->columnSpanFull(),

                            
                        ])->columnSpanFull(),
                        

                        // السطر الثالث: محتوى الدرس
                        Grid::make(1)->schema([
                            RichEditor::make('content')
                                ->label('Lesson Content')
                                ->toolbarButtons([
                                    'bold', 'italic', 'strike', 'link', 'h2', 'h3', 'bulletList', 'orderedList', 'redo', 'undo',
                                ]),
                        ]),
                        Hidden::make('course_id')
                            ->default(fn () => request()->query('course_id')) // التقاط ID الكورس من الرابط
                    ])->columnSpanFull(),
            ]);
    }
}
