<?php

namespace App\Filament\Instructor\Resources\Courses\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageCourseLessons extends ManageRelatedRecords
{
    protected static string $resource = CourseResource::class;

    protected static string $relationship = 'lessons';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-film';
    }

    // protected static ?string $recordTitleAttribute = 'Lessons';

    public static function getNavigationLabel(): string
    {
        return 'ادارة الدروس';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')->label('عنوان الدرس')->required()->maxLength(255),
                TextInput::make('video_url')->label('رابط الفيديو')->required()->maxLength(255),
                Textarea::make('content')->label('محتوى الدرس')->columnSpanFull(),
                Repeater::make('quizzes')
                    ->relationship('quiz')
                    ->label('اختبار الدرس (اختياري)')
                    ->maxItems(1) // 👈 عادة كل درس له اختبار واحد فقط
                    ->schema([
                        TextInput::make('title')->label('عنوان الاختبار')->default('اختبار الدرس')->required(),
                        TextInput::make('pass_mark')->label('درجة النجاح (%)')->numeric()->default(50)->required(),

                        // الأسئلة
                        Repeater::make('questions')
                            ->relationship('questions')
                            ->label('أسئلة الاختبار')
                            ->schema([
                                Textarea::make('question_text')->label('نص السؤال')->required()->columnSpanFull(),
                                // الإجابات
                                Repeater::make('answers')
                                    ->relationship('answers')
                                    ->label('خيارات الإجابة')
                                    ->schema([
                                        TextInput::make('answer_text')->label('نص الإجابة')->required(),
                                        Toggle::make('is_correct')->label('إجابة صحيحة؟')->default(false),
                                    ])
                                    ->columns(2)->minItems(2)->maxItems(4)->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? null)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->label('عنوان الدرس'),
                TextColumn::make('video_url')->label('رابط الفيديو')->limit(30),
            ])
            ->headerActions([
                // 👈 هنا السحر الحقيقي! النافذة الجانبية ستفتح لإضافة درس واحد فقط بأناقة
                CreateAction::make()->label('إضافة درس جديد')->slideOver(),
            ])
            ->actions([
                EditAction::make()->slideOver(),
                DeleteAction::make(),
            ])
            ->reorderable('order_number')
            ->defaultSort('order_number');
    }
}
