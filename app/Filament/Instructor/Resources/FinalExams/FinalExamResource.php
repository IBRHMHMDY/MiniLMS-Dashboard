<?php

namespace App\Filament\Instructor\Resources\FinalExams;


use App\Filament\Instructor\Resources\FinalExams\Pages\ListFinalExams;
use App\Filament\Instructor\Resources\FinalExams\Schemas\FinalExamForm;
use App\Filament\Instructor\Resources\FinalExams\Tables\FinalExamsTable;
use App\Models\Quiz;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FinalExamResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'FinalExams';

    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $slug = 'final-exams';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_final_exam', true) // جلب الاختبارات النهائية فقط
            ->whereHas('course', function (Builder $query) {
                $query->where('instructor_id', Auth::id());
            })
            ->withCount('questions');
    }


    public static function form(Schema $schema): Schema
    {
        return FinalExamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinalExamsTable::configure($table);
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
            'index' => ListFinalExams::route('/'),        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
