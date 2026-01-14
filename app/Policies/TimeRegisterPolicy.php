<?php
// app/Policies/TimeRegisterPolicy.php

namespace App\Policies;

use App\Models\TimeRegister;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimeRegisterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, TimeRegister $timeRegister)
    {
        if ($user->hasRole('Technical_Support_Group') || $user->hasRole('technical_support_group')) {
            return true;
        }

        if ($user->hasRole('staff') || $user->hasRole('field_staff')) {
            return $this->isInvolved($user, $timeRegister);
        }

        return $this->isInvolved($user, $timeRegister) || $user->hasAnyRole(['super-admin', 'Head_Logging_Services', 'Location Manager']);
    }

    public function create(User $user)
    {
        if ($user->hasRole('staff') || $user->hasRole('field_staff') || $user->hasRole('Technical_Support_Group') || $user->hasRole('technical_support_group')) {
            return false;
        }

        return $user->hasAnyRole(['super-admin', 'Field_Officer', 'field_officer', 'Head_Logging_Services', 'head_logging_services', 'Location Manager', 'location_manager']);
    }

    public function update(User $user, TimeRegister $timeRegister)
    {
        if ($user->hasRole('super-admin') || $user->hasRole('Head_Logging_Services') || $user->hasRole('head_logging_services') || $user->hasRole('Location Manager') || $user->hasRole('location_manager')) {
            return true;
        }

        if ($user->hasRole('staff') || $user->hasRole('field_staff') || $user->hasRole('Technical_Support_Group') || $user->hasRole('technical_support_group')) {
            return false;
        }

        if ($timeRegister->created_by === $user->id && !$timeRegister->is_final_submitted) {
            return true;
        }

        if ($timeRegister->logging_chief_id === $user->id && !$timeRegister->is_final_submitted) {
            return true;
        }

        if ($timeRegister->status === 'draft' && $user->hasAnyRole(['Field_Officer', 'field_officer']) && $this->isInvolved($user, $timeRegister)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, TimeRegister $timeRegister)
    {
        if ($timeRegister->is_final_submitted) {
            return false;
        }

        return $timeRegister->created_by === $user->id || $user->hasRole('super-admin');
    }

    public function restore(User $user, TimeRegister $timeRegister)
    {
        return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, TimeRegister $timeRegister)
    {
        return $user->hasRole('super-admin');
    }

    private function isInvolved(User $user, TimeRegister $timeRegister): bool
    {
        if ($user->id === $timeRegister->created_by) {
            return true;
        }

        if ($user->id === $timeRegister->logging_chief_id) {
            return true;
        }

        if ($timeRegister->jcr && $timeRegister->jcr->creator_id === $user->id) {
            return true;
        }

        return false;
    }
}