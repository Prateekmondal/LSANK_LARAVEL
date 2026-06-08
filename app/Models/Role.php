<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class Role extends SpatieRole
{
    /**
     * Dynamically resolve the DB connection based on whether tenancy is active.
     */
    public function getConnectionName()
    {
        if (tenancy()->initialized) {
            return config('database.default');
        }

        // IMPORTANT: Do NOT check request host here.
        // The hostname check was the root cause of the bug:
        // it returned 'tenant' before tenancy middleware had configured the
        // tenant DB connection, resulting in a null-database query crash.
        // Only return the tenant connection when tenancy is actually initialized.
        return 'central';
    }

    /**
     * Override delete() to manually clean up pivot tables on the correct
     * tenant connection BEFORE Spatie's deleting event fires.
     *
     * Spatie's HasPermissions trait registers a deleting event that calls
     * $role->users()->detach(). The users() relationship is defined as
     * morphedByMany(User::class, ...) and inherits User's $connection='central',
     * so the pivot query runs against lsank_laravel.model_has_roles — which does
     * not exist there (it's a tenant-only table).
     *
     * Solution: we pre-delete the pivot rows directly via DB::connection()
     * using the correct tenant connection. We then mark a flag so our
     * overridden users()->detach() becomes a no-op.
     */
    private bool $pivotAlreadyCleaned = false;

    public function delete(): ?bool
    {
        $connection = $this->getConnectionName();
        $registrar  = app(PermissionRegistrar::class);
        $pivotKey   = $registrar->pivotRole;

        // Step 1: Clean model_has_roles on the TENANT connection.
        DB::connection($connection)
            ->table(config('permission.table_names.model_has_roles', 'model_has_roles'))
            ->where($pivotKey, $this->id)
            ->delete();

        // Step 2: Clean role_has_permissions on the TENANT connection.
        DB::connection($connection)
            ->table(config('permission.table_names.role_has_permissions', 'role_has_permissions'))
            ->where($pivotKey, $this->id)
            ->delete();

        // Flush permission cache.
        $registrar->forgetCachedPermissions();

        // Mark that pivot cleanup is done so our users() no-op override
        // can safely ignore Spatie's subsequent detach() call.
        $this->pivotAlreadyCleaned = true;

        // Step 3: Run the actual Eloquent delete (fires Spatie's deleting event,
        // but pivot tables are already empty so detach() becomes a no-op).
        return parent::delete();
    }

    /**
     * Override the users() relationship to return a no-op relation when
     * pivot tables have already been cleaned. This prevents the central-
     * connection query error from Spatie's deleting event handler.
     */
    public function users(): BelongsToMany
    {
        if ($this->pivotAlreadyCleaned) {
            // Return a regular relation that has no rows to detach.
            // We point it at the Role's own connection so no central-DB query fires.
            return $this->morphedByMany(
                static::class,   // dummy: relation to Role itself, same connection
                'model',
                config('permission.table_names.model_has_roles'),
                app(PermissionRegistrar::class)->pivotRole,
                config('permission.column_names.model_morph_key')
            )->whereNull(config('permission.column_names.model_morph_key'));  // always empty
        }

        return parent::users();
    }
}