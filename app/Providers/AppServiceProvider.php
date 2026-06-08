<?php

namespace App\Providers;
use App\Models\Jcr;
use App\Models\ExplosiveChecklist;
use App\Models\TimeRegister;
use App\Policies\JcrPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
// use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Jcr::class => JcrPolicy::class,
        ExplosiveChecklist::class => \App\Policies\ChecklistPolicy::class,
        TimeRegister::class => \App\Policies\TimeRegisterPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\AuditLog::observe(\App\Observers\AuditLogObserver::class);

        // Livewire requests on tenant subdomains go through the custom
        // /livewire/update route defined in routes/tenant.php which already
        // includes InitializeTenancyByDomain. No addPersistentMiddleware needed.
        // Prevent non-super-admin users from deleting records via UI (including Filament)
        // and implicitly grant super-admins (central & tenant) all other permissions.
        Gate::before(function ($user, $ability) {
            $isCentralSuperAdmin = (bool) ($user->is_super_admin ?? false);
            $isTenantSuperAdmin = method_exists($user, 'hasRole') && $user->hasRole('super-admin');
            $isSuperAdmin = $isCentralSuperAdmin || $isTenantSuperAdmin;

            if (in_array($ability, ['delete', 'deleteAny'])) {
                if ($isSuperAdmin) {
                    return null; // allow super-admins to proceed to policy checks
                }
                return false; // deny delete abilities for other users
            }

            if ($isSuperAdmin) {
                return true; // Implicitly grant all other permissions to super-admins
            }

            return null;
        });
        Gate::define('create jcr', [JcrPolicy::class, 'create']);
        Gate::define('edit jcr', [JcrPolicy::class, 'update']);
        Gate::define('delete jcr', [JcrPolicy::class, 'delete']);
        Gate::define('sign_as_party_chief', [JcrPolicy::class, 'signAsPartyChief']);
        Gate::define('sign_as_operation_incharge', [JcrPolicy::class, 'signAsOperationIncharge']);
        Gate::define('approve jcr', [JcrPolicy::class, 'approve']);
        // Allow Technical Support Group (and super-admin) to push JCRs to SAP
        Gate::define('push_jcr_to_sap', function ($user) {
            if (method_exists($user, 'hasAnyRole')) {
                return $user->hasAnyRole(['Technical_Support_Group', 'super-admin']);
            }
            return false;
        });

        // Dynamic sitemap links for footer
        view()->composer('layouts.footer', function ($view) {
            $routes = collect(Route::getRoutes())->filter(function ($route) {
                $methods = $route->methods();
                if (!in_array('GET', $methods) && !in_array('HEAD', $methods)) {
                    return false;
                }
                $uri = $route->uri();
                if (Str::contains($uri, '{') || Str::startsWith($uri, '_')) {
                    return false;
                }
                if (Str::startsWith($uri, 'api') || Str::startsWith($uri, 'sanctum')) {
                    return false;
                }
                $middleware = $route->gatherMiddleware();
                if (in_array('auth', $middleware) || in_array(\App\Http\Middleware\Authenticate::class, $middleware)) {
                    return auth()->check();
                }
                return true;
            })->map(function ($r) {
                $label = $r->getName() ? Str::title(str_replace(['-','_','.'], ' ', $r->getName())) : Str::title(str_replace(['-','_','.','/'], ' ', $r->uri()));
                return ['label' => $label, 'url' => url($r->uri())];
            })->unique('url')->sortBy('label')->values();

            $view->with('sitemapLinks', $routes);
        });
    }

}
