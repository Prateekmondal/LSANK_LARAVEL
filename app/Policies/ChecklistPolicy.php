<?php

namespace App\Policies;

use App\Models\ExplosiveChecklist;
use App\Models\User;

class ChecklistPolicy
{
    public function viewAny(User $user)
    {
        return true; // listing is handled in controller by scopes
    }

    public function view(User $user, ExplosiveChecklist $checklist)
    {
        if ($user->hasRole('Technical_Support_Group') || $user->hasRole('technical_support_group')) {
            return true;
        }

        if ($user->hasRole('staff') || $user->hasRole('field_staff')) {
            return $this->isInvolved($user, $checklist);
        }

        return $this->isInvolved($user, $checklist) || $user->hasAnyRole(['super-admin', 'Head_Logging_Services', 'Location Manager']);
    }

    public function create(User $user)
    {
        if ($user->hasRole('staff') || $user->hasRole('field_staff') || $user->hasRole('Technical_Support_Group') || $user->hasRole('technical_support_group')) {
            return false;
        }

        return $user->hasAnyRole(['super-admin', 'Field_Officer', 'head_logging_services', 'Head_Logging_Services', 'Location Manager', 'location_manager']);
    }

    public function update(User $user, ExplosiveChecklist $checklist)
    {
        if ($user->hasRole('super-admin') || $user->hasRole('Head_Logging_Services') || $user->hasRole('head_logging_services') || $user->hasRole('Location Manager') || $user->hasRole('location_manager')) {
            return true;
        }

        if ($user->hasRole('staff') || $user->hasRole('field_staff') || $user->hasRole('Technical_Support_Group') || $user->hasRole('technical_support_group')) {
            return false;
        }

        if ($checklist->status === 'draft' && $user->id === $checklist->creator_id) {
            return true;
        }

        if ($checklist->status === 'draft' && $user->hasAnyRole(['Field_Officer', 'field_officer']) && $this->isInvolved($user, $checklist)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, ExplosiveChecklist $checklist)
    {
        if ($checklist->status !== 'draft') {
            return false;
        }

        return $user->id === $checklist->creator_id || $user->hasAnyRole(['super-admin', 'Head_Logging_Services']);
    }

    public function restore(User $user, ExplosiveChecklist $checklist)
    {
        return $user->hasAnyRole(['super-admin', 'Head_Logging_Services']);
    }

    public function forceDelete(User $user, ExplosiveChecklist $checklist)
    {
        return $user->hasRole('super-admin');
    }

    public function forceEdit(User $user)
    {
        return $user->hasAnyRole(['super-admin', 'Head_Logging_Services']);
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