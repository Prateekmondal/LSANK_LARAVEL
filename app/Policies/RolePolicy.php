<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Pre-authorize super-admins for ALL role actions before any individual
     * policy method is called. This eliminates 403 errors caused by
     * hasPermissionTo() race conditions during Livewire AJAX saves.
     *
     * Returning true here short-circuits ALL other policy methods below.
     * Returning null falls through to the individual policy methods.
     */
    public function before(User $user, string $ability): ?bool
    {
        // Central super-admin flag (no Spatie query needed)
        if ((bool) ($user->is_super_admin ?? false)) {
            return true;
        }

        // Tenant super-admin: check role safely only when tenancy is initialized
        if (tenancy()->initialized && method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
            return true;
        }

        return null; // Fall through to individual policy methods
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_shield::role');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('view_shield::role');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_shield::role');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('update_shield::role');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('delete_shield::role');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_any_shield::role');
    }
}
