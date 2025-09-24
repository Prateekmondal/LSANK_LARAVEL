<?php

namespace App\Policies;

use App\Models\ExplosiveChecklist;
use App\Models\User;

class ChecklistPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, ExplosiveChecklist $checklist)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, ExplosiveChecklist $checklist)
    {
        return $checklist->status === 'draft' && 
               ($user->id === $checklist->creator_id || 
                $user->hasAnyRole(['super-admin', 'head-logging-service']));
    }

    public function delete(User $user, ExplosiveChecklist $checklist)
    {
        return $this->update($user, $checklist);
    }

    public function restore(User $user, ExplosiveChecklist $checklist)
    {
        return $user->hasAnyRole(['super-admin', 'head-logging-service']);
    }

    public function forceDelete(User $user, ExplosiveChecklist $checklist)
    {
        return $user->hasRole('super-admin');
    }

    public function forceEdit(User $user)
    {
        return $user->hasAnyRole(['super-admin', 'head-logging-service']);
    }
}