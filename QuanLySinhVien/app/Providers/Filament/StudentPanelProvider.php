<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureUserIsStudent;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StudentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('student')
            ->path('student')
            ->login(\App\Filament\Pages\Auth\StudentLogin::class)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->pages([
                \App\Filament\Student\Pages\Profile::class,
                \App\Filament\Student\Pages\Scoreboard::class,
            ])
            ->discoverPages(in: app_path('Filament/Student/Pages'), for: 'App\\Filament\\Student\\Pages')
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
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureUserIsStudent::class,
            ])
            ->authGuard('web')
            ->sidebarCollapsibleOnDesktop()
            ->brandName('Hệ thống Quản lý Sinh viên');
    }
}

