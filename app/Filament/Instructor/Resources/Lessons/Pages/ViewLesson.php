<?php

namespace App\Filament\Instructor\Resources\Lessons\Pages;

use App\Filament\Instructor\Resources\Lessons\LessonResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLesson extends ViewRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
