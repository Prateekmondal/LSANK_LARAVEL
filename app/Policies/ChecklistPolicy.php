<?php

namespace App\Policies;

use App\Models\ExplosiveChecklist;
use App\Models\User;

class ChecklistPolicy
{
    /**
     * Grant super-admins all abilities without further checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ((bool) ($user->is_super_admin ?? false)) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user)
    {
        return true; // listing is scoped in controller/resource queries
    }

    public function view(User $user, ExplosiveChecklist $checklist)
    {
        if ($user->hasAnyRole(['technical_support_group', 'Technical_Support_Group'])) {
            return true;
        }

        if ($user->hasAnyRole(['field_staff', 'staff'])) {
            return $this->isInvolved($user, $checklist);
        }

        return $this->isInvolved($user, $checklist)
            || $user->hasAnyRole(['super-admin', 'head_logging_services', 'Head_Logging_Services', 'location_manager', 'Location Manager']);
    }

    public function create(User $user)
    {
        // The Filament Shield permission is the authoritative gate.
        // Role names must match exactly what's in the tenant DB (all lowercase).
        return $user->hasPermissionTo('create_explosive::checklist')
            || $user->hasAnyRole([
                'super-admin',
                'field_officer',       // actual DB name
                'Field_Officer',       // legacy / alternate casing kept for safety
                'head_logging_services',
                'Head_Logging_Services',
                'location_manager',
                'Location Manager',
            ]);
    }

    public function update(User $user, ExplosiveChecklist $checklist)
    {
        if ($user->hasAnyRole(['super-admin', 'head_logging_services', 'Head_Logging_Services', 'location_manager', 'Location Manager'])) {
            return true;
        }

        if (!$user->hasPermissionTo('update_explosive::checklist')) {
            return false;
        }

        if ($checklist->status === 'draft' && $user->id === $checklist->creator_id) {
            return true;
        }

        if ($checklist->status === 'draft' && $user->hasAnyRole(['field_officer', 'Field_Officer']) && $this->isInvolved($user, $checklist)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, ExplosiveChecklist $checklist)
    {
        if ($checklist->status !== 'draft') {
            return false;
        }

        return $user->id === $checklist->creator_id
            || $user->hasAnyRole(['super-admin', 'head_logging_services', 'Head_Logging_Services']);
    }

    public function restore(User $user, ExplosiveChecklist $checklist)
    {
        return $user->hasAnyRole(['super-admin', 'head_logging_services', 'Head_Logging_Services']);
    }

    public function forceDelete(User $user, ExplosiveChecklist $checklist)
    {
        return $user->hasRole('super-admin');
    }

    public function forceEdit(User $user)
    {
        return $user->hasAnyRole(['super-admin', 'head_logging_services', 'Head_Logging_Services']);
    }

    private function isInvolved(User $user, ExplosiveChecklist $checklist): bool
    {
        if ($user->id === $checklist->creator_id) {
            return true;
        }

        if ($checklist->signatures()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($checklist->forwards()->where('to_user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }
}