<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationGroup;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\ActiveUsersWidget;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class HubPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('hub')
            ->path('hub')
            ->login()
            ->brandLogo(asset('images/logo3.png'))
            ->brandName('BGC CONNECT')
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/LOGO2.png'))
            ->font('Roboto Mono')
            ->sidebarFullyCollapsibleOnDesktop()
           
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'primary' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Members')
                    ->icon('heroicon-o-user-group'), // user/profile icon
                NavigationGroup::make()
                    ->label('Chat')
                    ->icon('heroicon-o-chat-bubble-left-right'), // chat/message icon
                NavigationGroup::make()
                    ->label('Events')
                    ->icon('heroicon-o-calendar-days'), // user/profile icon
                NavigationGroup::make()
                    ->label('Admin Options')
                    ->icon('heroicon-o-cog'), // user/profile icon
            ])
            ->plugins([
                LightSwitchPlugin::make(),
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(
                        MyImages::make()
                            ->directory('images/backgrounds')
                    ),

                
            ]);
    }
}
