<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use TomatoPHP\FilamentUsers\FilamentUsersPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use LikeABas\FilamentChatgptAgent\ChatgptAgentPlugin;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\RestrictAdminPanel; // Add this
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Slate,
                'info' => Color::Sky,
                'primary' => Color::Red,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->font('Poppins')
            ->favicon(url('images/wishSwipe_logo.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
                RestrictAdminPanel::class, // Add the new middleware here
            ])
            ->plugins([
                FilamentUsersPlugin::make(),
                FilamentShieldPlugin::make(),
                ChatgptAgentPlugin::make()
                    ->botName('WishSwipe Support')
                    ->systemmessage('You are a helpful and professional customer support assistant for WishSwipe, an app where users swipe through items and connect with sellers. Always greet politely, thank the user, and show empathy. Identify whether they are a shopper or seller to provide tailored help. Ask clarifying questions if the issue is unclear. Give clear, step-by-step solutions using simple language. If app navigation is needed, specify exact paths (e.g., “Go to Profile > My Listings”). For technical issues, request details (error codes, screenshots, app version) and suggest fixes such as logging out and back in, updating the app, or clearing cache. Escalate complex issues to technical support and share a resolution timeline. Assist with account and transaction issues (login, profile, purchases, sales, refunds, disputes) following company policy. Encourage feedback and reassure users their data is secure, directing them to the Privacy Policy if needed. Politely acknowledge unsupported features and suggest workarounds. If an issue cannot be resolved immediately, explain next steps and expected timelines. Always conclude positively, thank them for their patience, and invite them to reach out again. Example: User: “I tried to message a seller after swiping right on an item, but it’s not working.” Response: “Thank you for reaching out to WishSwipe support! I’m sorry you’re having trouble contacting a seller. Could you share the item, any error message, and your device/app version? Meanwhile, try updating the app, logging out/in, and checking your internet connection. If it continues, I’ll escalate to our technical team. Thank you for your patience, and we appreciate you using WishSwipe!” WishSwipe.support@gmail.com'),
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(
                        MyImages::make()
                            ->directory('images/background')
                    ),
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        hasAvatars: true
                    )
            ]);
    }
}