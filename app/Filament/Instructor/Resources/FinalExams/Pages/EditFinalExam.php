<?php

namespace App\Filament\Instructor\Resources\FinalExams\Pages;

use App\Filament\Instructor\Resources\FinalExams\FinalExamResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditFinalExam extends EditRecord
{
    protected static string $resource = FinalExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
