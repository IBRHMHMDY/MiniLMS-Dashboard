<?php

namespace App\Providers\Filament;

use App\Http\Middleware\ForceInstructorPanelEnglish;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class InstructorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('instructor')
            ->path('instructor')
            ->login()
            ->profile() // تفعيل صفحة البروفايل للمدرب
            ->colors([
                'primary' => Color::Indigo, // يمكنك تغيير لون اللوحة الأساسي من هنا
            ])
            ->viteTheme('resources/css/filament/instructor/theme.css')
            ->maxContentWidth('4xl') // تعيين الحد الأقصى لعرض المحتوى
            ->discoverResources(in: app_path('Filament/Instructor/Resources'), for: 'App\\Filament\\Instructor\\Resources')
            ->discoverPages(in: app_path('Filament/Instructor/Pages'), for: 'App\\Filament\\Instructor\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Instructor/Widgets'), for: 'App\\Filament\\Instructor\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                ForceInstructorPanelEnglish::class, // تطبيق اللغة الإنجليزية
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // إخفاء اسم النظام الافتراضي لكي نستخدم تصميمنا المخصص
            ->brandName('')
            ->brandLogo(fn () => view('filament.instructor.brand'))
            ->brandLogoHeight('4rem')
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): string => view('filament.instructor.topbar-end')->render()
            );
    }
}