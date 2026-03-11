<?php

namespace App\Filament\Instructor\Resources\Quizzes;

use App\Filament\Instructor\Resources\Quizzes\Pages\ListQuizzes;
use App\Filament\Instructor\Resources\Quizzes\Schemas\QuizForm;
use App\Filament\Instructor\Resources\Quizzes\Schemas\QuizInfolist;
use App\Filament\Instructor\Resources\Quizzes\Tables\QuizzesTable;
use App\Models\Quiz;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Quiz';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('lesson.course', function (Builder $query) {
                $query->where('instructor_id', Auth::id());
            })
            ->withCount('questions')
            ->withoutGlobalScopes([
                SoftDeletingScope::class, // ضروري جداً لعمل فلتر سلة المحذوفات
            ]);
    }


    public static function form(Schema $schema): Schema
    {
        return QuizForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuizInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizzesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuizzes::route('/'),
        ];
    }
}
