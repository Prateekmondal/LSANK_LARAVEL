<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan assign:role {cpf} {role} {--tenant= : Tenant ID (slug). Required for tenant-specific roles.}
     * php artisan assign:role {cpf} {role} --all-tenants  : assign role in ALL tenant databases
     */
    protected $signature = 'assign:role
                            {cpf : The CPF (employee ID) of the user}
                            {role : The role name to assign (e.g. super-admin, location_manager, field_officer)}
                            {--tenant= : Assign role inside a specific tenant database (e.g. ankleshwar)}
                            {--all-tenants : Assign role inside ALL tenant databases}';

    protected $description = 'Assign a Spatie role to a central user inside a specific or all tenant databases.';

    public function handle(): int
    {
        $cpf    = $this->argument('cpf');
        $role   = $this->argument('role');
        $tenantId  = $this->option('tenant');
        $allTenants = $this->option('all-tenants');

        // Find the user from the central DB
        $user = User::where('cpf', $cpf)->first();
        if (! $user) {
            $this->error("❌ No user found with CPF: {$cpf}");
            return 1;
        }

        $this->info("👤 User found: {$user->name} (CPF: {$user->cpf}, ID: {$user->id})");

        if (! $tenantId && ! $allTenants) {
            $this->error('❌ You must specify --tenant=<tenant-id> or --all-tenants.');
            return 1;
        }

        $tenants = $allTenants
            ? Tenant::all()
            : Tenant::where('id', $tenantId)->get();

        if ($tenants->isEmpty()) {
            $this->error("❌ No tenant(s) found" . ($tenantId ? " for ID: {$tenantId}" : '') . '.');
            return 1;
        }

        foreach ($tenants as $tenant) {
            $this->line("  → Initializing tenant: <comment>{$tenant->id}</comment>");

            tenancy()->initialize($tenant);

            try {
                // Ensure the role exists in this tenant's database
                $roleModel = Role::where('name', $role)->first();
                if (! $roleModel) {
                    $this->warn("     ⚠️  Role '{$role}' does not exist in tenant '{$tenant->id}'. Skipping.");
                    tenancy()->end();
                    continue;
                }

                // Check if already assigned (using the tenant's model_has_roles table)
                $alreadyAssigned = \Illuminate\Support\Facades\DB::table('model_has_roles')
                    ->where('role_id', $roleModel->id)
                    ->where('model_type', User::class)
                    ->where('model_id', $user->id)
                    ->exists();

                if ($alreadyAssigned) {
                    $this->line("     ℹ️  Already has role '{$role}' in tenant '{$tenant->id}'.");
                    tenancy()->end();
                    continue;
                }

                // Insert directly into tenant's model_has_roles pivot table
                \Illuminate\Support\Facades\DB::table('model_has_roles')->insert([
                    'role_id'    => $roleModel->id,
                    'model_type' => User::class,
                    'model_id'   => $user->id,
                ]);

                $this->info("     ✅ Role '{$role}' assigned to {$user->name} in tenant '{$tenant->id}'.");
            } catch (\Throwable $e) {
                $this->error("     ❌ Failed in tenant '{$tenant->id}': " . $e->getMessage());
            }

            tenancy()->end();
        }

        return 0;
    }
}
