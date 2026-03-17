<?php

namespace App\Filament\Instructor\Resources\FinalExams\Schemas;

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
                Section::make(__('Final Exam Content'))
                    ->description(__('Build the final exam for this course.'))
                    ->relationship('finalExam')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label(__('Exam Title'))
                                ->default('Final Exam')
                                ->required(),

                            TextInput::make('pass_mark')
                                ->label(__('Passing Grade (%)'))
                                ->numeric()
                                ->default(50)
                                ->minValue(1)
                                ->maxValue(100)
                                ->required(),
                        ]),

                        Toggle::make('is_published')
                            ->label(__('Publish Exam'))
                            ->default(true),

                        // Nested Repeater للأسئلة والإجابات
                        Repeater::make('questions')
                            ->relationship('questions')
                            ->schema([
                                TextInput::make('question_text')
                                    ->label(__('Question'))
                                    ->required()
                                    ->columnSpanFull(),

                                Repeater::make('answers')
                                    ->relationship('answers')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('answer_text')
                                                ->label(__('Answer'))
                                                ->required()
                                                ->columnSpan(2),

                                            Toggle::make('is_correct')
                                                ->label(__('Correct Answer?'))
                                                ->inline(false)
                                                ->columnSpan(1),
                                        ])
                                    ])
                                    ->defaultItems(2)
                                    ->addActionLabel(__('Add Answer'))
                                    ->columnSpanFull()
                                    ->itemLabel(fn (array $state): ?string => $state['answer_text'] ?? null)
                                    ->rule(function () {
                                        return function (string $attribute, $value, \Closure $fail) {
                                            $hasCorrectAnswer = collect($value)->where('is_correct', true)->count() > 0;
                                            if (!$hasCorrectAnswer) {
                                                $fail(__('You must select at least one correct answer.'));
                                            }
                                        };
                                    }),
                            ])
                            ->addActionLabel(__('Add Question'))
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? null)
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull()
                    ])
            ]);
    }
}
