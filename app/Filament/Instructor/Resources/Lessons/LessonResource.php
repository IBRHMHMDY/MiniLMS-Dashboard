<?php

namespace App\Filament\Instructor\Resources\Lessons;

use App\Filament\Instructor\Resources\Lessons\Pages\EditLesson as PagesEditLesson;
use App\Filament\Instructor\Resources\Lessons\Pages\ListLessons;
use App\Filament\Instructor\Resources\Lessons\Schemas\LessonForm;
use App\Filament\Instructor\Resources\Lessons\Schemas\LessonInfolist;
use App\Filament\Instructor\Resources\Lessons\Tables\LessonsTable;
use App\Filament\Instructor\Resources\Lessons\Pages\CreateLesson as PagesCreateLesson;
use App\Models\Lesson;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Lesson';

    protected static ?string $navigationLabel = 'My Lessons';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('course', function (Builder $query) {
                $query->where('instructor_id', Auth::id());
            })
            ->withCount('quiz');
    }

    public static function form(Schema $schema): Schema
    {
        return LessonForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LessonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LessonsTable::configure($table);
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
            'index' => ListLessons::route('/'),
            'create' => PagesCreateLesson::route('/create'),
            'edit' => PagesEditLesson::route('/{record}/edit'),
        ];
    }
}
