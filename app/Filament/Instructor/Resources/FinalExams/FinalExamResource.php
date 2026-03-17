<?php

namespace App\Filament\Instructor\Resources\FinalExams;

use App\Filament\Instructor\Resources\FinalExams\Schemas\FinalExamForm;
use App\Filament\Instructor\Resources\FinalExams\Tables\FinalExamsTable;
use App\Models\Course;
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
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'FinalExams';

    protected static ?string $navigationLabel = 'Final Exams';
    
    protected static ?string $slug = 'final-exams';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('instructor_id', Auth::id());
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
            'index' => Pages\ListFinalExams::route('/'),
            'edit' => Pages\EditFinalExam::route('/{record}/edit'),    
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
