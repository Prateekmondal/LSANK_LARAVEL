<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Concerns\HasDomainIdentifier;

class CreateTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenantId;
    protected $tenantData;

    public function __construct($tenantId, array $tenantData = [])
    {
        $this->tenantId = $tenantId;
        $this->tenantData = $tenantData;
    }

    public function handle()
    {
        try {
            $tenant = Tenant::find($this->tenantId);
            
            if (!$tenant) {
                throw new \Exception("Tenant with ID {$this->tenantId} not found");
            }

            // Initialize tenancy for this specific tenant
            tenancy()->initialize($tenant);

            // Run migrations for this tenant
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force' => true,
            ]);

            // Run seeders for this tenant
            Artisan::call('db:seed', [
                '--database' => 'tenant',
                '--force' => true,
            ]);

            // Mark tenant as ready
            $tenant->update(['is_active' => true]);

            // Forgetfulness of tenancy context
            tenancy()->end();

        } catch (\Exception $e) {
            // Log the error
            \Log::error("Failed to create tenant {$this->tenantId}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Optional: Notify admin or implement retry logic
            throw $e;
        }
    }
}
