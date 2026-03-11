<?php

namespace App\Filament\Instructor\Resources\Quizzes\Schemas;

use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Quiz Information')
                    ->schema([
                        
                            TextInput::make('title')
                                ->label('Quiz Title')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('pass_mark')
                                ->label('Passing Grade')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(100)
                                ->suffix('%')
                                ->required(),
                        

                        Hidden::make('lesson_id')
                            ->default(fn (\Livewire\Component $livewire) => property_exists($livewire, 'lesson_id') ? $livewire->lesson_id : request()->query('lesson_id'))
                            ->dehydrated(true),

                        Hidden::make('course_id')
                            ->default(function (\Livewire\Component $livewire) {
                                $lessonId = property_exists($livewire, 'lesson_id') ? $livewire->lesson_id : request()->query('lesson_id');
                                return \App\Models\Lesson::find($lessonId)?->course_id;
                            })
                            ->dehydrated(true),

                        // تم إزالة شرط "اختبار واحد فقط" بناءً على طلبك
                        Toggle::make('is_published')
                            ->label('Publish Quiz')
                            ->inline(false),
                    

                // 2. القسم الثاني: الأسئلة والإجابات
                Section::make('Quiz Questions & Answers')
                    ->description('Click here to manage questions for this quiz.')
                    ->icon('heroicon-o-list-bullet')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                TextInput::make('question_text')
                                    ->label('Question')
                                    ->required()
                                    ->columnSpanFull(),

                                Repeater::make('answers')
                                    ->relationship()
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('answer_text')
                                                ->label('Answer')
                                                ->required()
                                                ->columnSpan(2),

                                            Toggle::make('is_correct')
                                                ->label('Correct Answer?')
                                                ->inline(false)
                                                ->columnSpan(1),
                                        ])
                                    ])
                                    ->defaultItems(2)
                                    ->addActionLabel('Add Answer')
                                    ->columnSpanFull()
                                    ->itemLabel(fn (array $state): ?string => $state['answer_text'] ?? null)
                                    ->collapsible()
                                    // إضافة قاعدة التحقق (Validation Rule) لإجبار المدرب على اختيار إجابة صحيحة واحدة على الأقل
                                    ->rule(function () {
                                        return function (string $attribute, $value, \Closure $fail) {
                                            $hasCorrectAnswer = collect($value)->where('is_correct', true)->count() > 0;
                                            if (!$hasCorrectAnswer) {
                                                $fail('You must select at least one correct answer for this question.');
                                            }
                                        };
                                    }),
                            ])
                            ->addActionLabel('Add New Question')
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? null)
                            ->collapsible()
                            ->defaultItems(0)
                    ]),
                    ])->columnSpanFull(),
            ]);
    }
}
