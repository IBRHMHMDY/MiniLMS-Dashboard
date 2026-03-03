<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('تفاصيل الاختبار')->schema([
                    Select::make('course_id')
                        ->relationship('course', 'title', fn (Builder $query) =>
                                    // عزل البيانات: المدرب يرى كورساته فقط، والأدمن يرى الجميع
                                    auth()->user()->hasRole('instructor')
                                        ? $query->where('instructor_id', auth()->id())
                                        : $query
                        )
                        ->required()
                        ->unique(ignoreRecord: true) // بافتراض اختبار واحد لكل كورس للتبسيط
                        ->label('الكورس المرتبط'),
                    TextInput::make('title')
                        ->required()
                        ->label('عنوان الاختبار')
                        ->maxLength(255),
                    TextInput::make('pass_mark')
                        ->required()
                        ->numeric()
                        ->default(50)
                        ->minValue(1)
                        ->maxValue(100)
                        ->label('درجة النجاح (%)'),
                ])->columns(3),
                Section::make('الأسئلة والإجابات')->schema([
                    Repeater::make('questions')
                        ->relationship() // ترتبط بعلاقة questions() في موديل Quiz
                        ->label('الأسئلة')
                        ->schema([
                            TextInput::make('question_text')
                                ->required()
                                ->label('نص السؤال')
                                ->columnSpanFull(),

                            // مكرر داخلي للإجابات
                            Repeater::make('answers')
                                ->relationship() // ترتبط بعلاقة answers() في موديل Question
                                ->label('خيارات الإجابة')
                                ->schema([
                                    TextInput::make('answer_text')
                                        ->required()
                                        ->label('نص الإجابة')
                                        ->columnSpan(3),

                                    Toggle::make('is_correct')
                                        ->label('إجابة صحيحة؟')
                                        ->inline(false)
                                        ->columnSpan(1),
                                ])
                                ->columns(4)
                                ->defaultItems(4), // افتراضياً 4 خيارات لكل سؤال
                            // ->addActionLabel('إضافة خيار جديد'),
                        ])
                        ->addActionLabel('إضافة سؤال جديد')
                        ->collapsible() // لجعل الأسئلة قابلة للطي لترتيب الشاشة
                        ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? 'سؤال جديد'),
                ]),
            ]);
    }
}
