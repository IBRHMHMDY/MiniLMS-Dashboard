<?php

namespace App\Filament\Instructor\Resources\Courses\Pages;

use App\Filament\Instructor\Resources\Courses\CourseResource;
use BackedEnum;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    
    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl('index') => 'My Courses',
            'Edit Course',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Save Changes & Go to Dashboard'),

            Action::make('save_and_add_lesson')
                ->label('Save & Add Lesson')
                ->color('success')
                ->icon('heroicon-o-plus-circle')
                ->action(function () {
                    $this->save();
                    $this->redirect('/instructor/lessons/create?course_id=' . $this->record->id);
                }),


        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}