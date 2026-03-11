<?php

namespace App\Filament\Instructor\Resources\FinalExams\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FinalExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Final Exam Information')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Exam Title')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('pass_mark')
                                ->label('Passing Grade')
                                ->default(50)
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(100)
                                ->suffix('%')
                                ->required(),
                        ]),

                        // حفظ رقم الكورس من ذاكرة الصفحة
                        Hidden::make('course_id')
                            ->default(fn (\Livewire\Component $livewire) => property_exists($livewire, 'course_id') ? $livewire->course_id : request()->query('course_id'))
                            ->dehydrated(true),

                        // إجبار الاختبار ليكون اختباراً نهائياً
                        Hidden::make('is_final_exam')
                            ->default(true)
                            ->dehydrated(true),

                        Toggle::make('is_published')
                            ->label('Publish Final Exam')
                            ->inline(false),
                    ]),

                Section::make('Exam Questions & Answers')
                    ->description('Click here to manage questions for this final exam.')
                    ->icon('heroicon-o-academic-cap')
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
                                    ->defaultItems(4)
                                    ->addActionLabel('Add Answer')
                                    ->columnSpanFull()
                                    ->itemLabel(fn (array $state): ?string => $state['answer_text'] ?? null)
                                    ->collapsible()
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
                            ->collapsed()
                            ->defaultItems(0)
                    ]),
            
            ]);
    }
}
