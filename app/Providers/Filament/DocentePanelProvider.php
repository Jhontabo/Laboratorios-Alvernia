<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class DocentePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('docente') // Cambia el ID para identificarlo correctamente
            ->path('docente') // Cambia la ruta base del panel
            ->login()
            ->profile(false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->databaseNotifications()
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->slug('mi-perfil')
                    ->setTitle('Mi perfil')
                    ->setNavigationLabel('Mi perfil')
                    ->setIcon('heroicon-o-user')
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowBrowserSessionsForm(false)
                    ->shouldShowEditPasswordForm(false)
                    ->shouldShowAvatarForm(true, 'avatars', 'mimes:jpeg,png|max:1024')
                    ->customProfileComponents([
                        \App\Livewire\CustomProfileComponent::class
                    ])
            ])
            ->plugin(\Hasnayeen\Themes\ThemesPlugin::make())
            ->plugin(FilamentFullCalendarPlugin::make()
                ->schedulerLicenseKey('')
                ->selectable(true)
                ->editable(true)
                ->timezone(config('app.timezone'))
                ->locale(config('app.locale'))
                ->plugins(['dayGrid', 'timeGrid'])
                ->config([
                    'dayMaxEvents' => true,
                    'moreLinkClick' => 'day'
                ])
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources') // Mismos recursos
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
                SetTheme::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}