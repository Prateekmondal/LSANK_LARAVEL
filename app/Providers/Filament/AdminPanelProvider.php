<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Filament\Pages\Auth\CpfLogin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use App\Http\Middleware\InitializeTenancyByDomainOrSkipForCentral;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $isCentralDomain = in_array(request()->getHost(), config('tenancy.central_domains', []));

        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CpfLogin::class)
            ->colors([
                'primary' => Color::Amber,
            ]);

        if ($isCentralDomain) {
            $panel->resources([
                \App\Filament\Resources\TenantResource::class,
            ]);
        } else {
            // Dynamically discover all resources EXCEPT TenantResource
            $resources = [];
            $resourceFiles = glob(app_path('Filament/Resources/*.php'));
            foreach ($resourceFiles as $file) {
                $className = 'App\\Filament\\Resources\\' . basename($file, '.php');
                if ($className !== \App\Filament\Resources\TenantResource::class && class_exists($className)) {
                    $resources[] = $className;
                }
            }
            $panel->resources($resources);
        }

        $plugins = [];
        if (!$isCentralDomain) {
            $plugins[] = \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make();
        }

        return $panel
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->middleware([
                // Add the Tenancy middlewares here
                InitializeTenancyByDomainOrSkipForCentral::class,

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
            ->plugins($plugins);
    }
}
