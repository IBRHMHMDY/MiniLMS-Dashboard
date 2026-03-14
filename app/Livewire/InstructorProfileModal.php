<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms; // استدعاء هام
use Filament\Forms\Contracts\HasForms;         // استدعاء هام
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

// إضافة HasForms هنا
class InstructorProfileModal extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms; // إضافة التريت هنا

    #[On('open-profile-modal')]
    public function openProfileModal(): void
    {
        $this->mountAction('editProfile');
    }

    public function editProfileAction(): Action
    {
        return Action::make('editProfile')
            ->modalHeading('Edit My Profile')
            ->modalWidth('md')
            ->modalSubmitActionLabel('Save Changes')
            ->fillForm(fn () => Filament::auth()->user()->toArray())
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('password')
                    ->label('New Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText('Leave blank to keep your current password.'),
            ])
            ->action(function (array $data) {
                $user = Filament::auth()->user();
                
                if (filled($data['password'] ?? null)) {
                    $data['password'] = Hash::make($data['password']);
                } else {
                    unset($data['password']);
                }
                
                $user->update($data);
                
                Notification::make()
                    ->title('Profile updated successfully')
                    ->success()
                    ->send();
            });
    }

    public function render()
    {
        return <<<'BLADE'
            <div>
                <x-filament-actions::modals />
            </div>
        BLADE;
    }
}