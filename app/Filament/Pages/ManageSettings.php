<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.pages.manage-settings';

    protected static string|UnitEnum|null $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 10;
    
    protected static ?string $title = 'Platform Global Settings';

    public static function getNavigationLabel(): string
    {
        return __('Global Settings');
    }

    public ?array $data = [];

    // جلب الإعدادات الحالية من قاعدة البيانات عند فتح الصفحة
    public function mount(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('General Information'))
                    ->schema([
                        TextInput::make('platform_name')
                            ->label(__('Platform Name'))
                            ->required(),
                        TextInput::make('default_commission')
                            ->label(__('Default Platform Commission (%)'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required()
                            ->helperText(__('This percentage applies to platform earnings.')),
                        FileUpload::make('platform_logo')
                            ->label(__('Platform Logo'))
                            ->image()
                            ->directory('settings')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make(__('Social Links'))
                    ->schema([
                        TextInput::make('facebook_link')->url()->label('Facebook URL'),
                        TextInput::make('twitter_link')->url()->label('Twitter URL'),
                        TextInput::make('linkedin_link')->url()->label('LinkedIn URL'),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    // حفظ البيانات بطريقة Key-Value
    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Notification::make()
            ->success()
            ->title(__('Settings Saved'))
            ->body(__('Global system settings have been updated successfully.'))
            ->send();
    }
}