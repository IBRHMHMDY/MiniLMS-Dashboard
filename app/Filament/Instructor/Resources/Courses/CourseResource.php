<?php

namespace App\Filament\Instructor\Resources\Courses;

use App\Filament\Instructor\Resources\Courses\Pages\CreateCourse;
use App\Filament\Instructor\Resources\Courses\Pages\EditCourse;
use App\Filament\Instructor\Resources\Courses\Pages\ListCourses;
use App\Filament\Instructor\Resources\Courses\Pages\ManageCourseLessons;
use App\Filament\Instructor\Resources\Courses\Pages\ManageCourseQuizzes;
use App\Filament\Instructor\Resources\Courses\Schemas\CourseForm;
use App\Filament\Instructor\Resources\Courses\Tables\CoursesTable;
use App\Models\Course;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Course';

    public static function form(Schema $schema): Schema
    {
        return CourseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'edit' => EditCourse::route('/{record}/edit'),
            'lessons' => ManageCourseLessons::route('/{record}/lessons'),
            'quizzes' => ManageCourseQuizzes::route('/{record}/quizzes'),
        ];
    }

    public static function getRecordSubNavigation(\Filament\Resources\Pages\Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ListCourses::class,
            // Pages\EditCourse::class,
            Pages\ManageCourseLessons::class,
            Pages\ManageCourseQuizzes::class,
        ]);
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getEloquentQuery(): Builder
    {
        // حصر البيانات: جلب الكورسات التي يملكها المدرب المسجل دخوله حالياً فقط
        return parent::getEloquentQuery()->where('instructor_id', auth()->id());
    }
}
