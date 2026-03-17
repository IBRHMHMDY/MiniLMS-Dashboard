<?php

namespace App\Filament\Instructor\Resources\FinalExams\Pages;

use App\Filament\Instructor\Resources\FinalExams\FinalExamResource;
use Filament\Resources\Pages\EditRecord;

class EditFinalExam extends EditRecord
{
    protected static string $resource = FinalExamResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label(__('Save Exam')),
            $this->getCancelFormAction(),
        ];
    }
}
