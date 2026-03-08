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
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageCourseQuizzes extends ManageRelatedRecords
{
    protected static string $resource = CourseResource::class;

    protected static string $relationship = 'quiz';

    // protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlineAcademicCap;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getNavigationLabel(): string
    {
        return 'ادارة الاختبارات';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')->label('عنوان الاختبار')->required(),
                TextInput::make('pass_mark')->label('درجة النجاح (%)')->numeric()->default(50)->required(),

                Repeater::make('questions')
                    ->relationship('questions')
                    ->label('أسئلة الاختبار')
                    ->schema([
                        Textarea::make('question_text')->label('نص السؤال')->required()->columnSpanFull(),
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->label('عنوان الاختبار'),
                TextColumn::make('pass_mark')->label('درجة النجاح')->badge()->color('success'),
            ])
            ->headerActions([
                CreateAction::make()->label('إضافة اختبار جديد')->slideOver(),
            ])
            ->actions([
                EditAction::make()->slideOver(),
                DeleteAction::make(),
            ]);
    }
}
