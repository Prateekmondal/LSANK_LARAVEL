<?php

namespace App\Providers;
use App\Models\Jcr;
use App\Policies\JcrPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
        \App\Models\AuditLog::observe(\App\Observers\AuditLogObserver::class);
        // Gate definitions for role-based access
        Gate::define('create jcr', [JcrPolicy::class, 'create']);
        Gate::define('edit jcr', [JcrPolicy::class, 'update']);
        Gate::define('delete jcr', [JcrPolicy::class, 'delete']);
        Gate::define('sign_as_party_chief', [JcrPolicy::class, 'signAsPartyChief']);
        Gate::define('sign_as_operation_incharge', [JcrPolicy::class, 'signAsOperationIncharge']);
        Gate::define('approve jcr', [JcrPolicy::class, 'approve']);
    }

}
