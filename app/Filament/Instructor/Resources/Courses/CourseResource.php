<?php

namespace App\Filament\Instructor\Resources\Courses;

use App\Filament\Instructor\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Instructor\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Instructor\Resources\CourseResource\Pages\ListCourses;
use App\Filament\Instructor\Resources\Courses\Schemas\CourseForm;
use App\Filament\Instructor\Resources\Courses\Schemas\CourseInfolist;
use App\Filament\Instructor\Resources\Courses\Tables\CoursesTable;
use App\Models\Course;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Course';

    protected static ?string $navigationLabel = 'My Courses';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('instructor_id', Auth::id())
            ->withCount('enrollments');
    }
    
    public static function form(Schema $schema): Schema
    {
        return CourseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
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
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'edit' => EditCourse::route('/{record}/edit'),
        ];
    }
}
