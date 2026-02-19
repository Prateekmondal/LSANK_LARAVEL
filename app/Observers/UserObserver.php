<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        // If is_approved is being changed from false to true, set approved_at and approved_by
        if ($user->isDirty('is_approved') && $user->is_approved && !$user->getOriginal('is_approved')) {
            $user->approved_at = now();
            $user->approved_by = auth()->id();
        }

        // If is_approved is being changed from true to false, clear approved_at and approved_by
        if ($user->isDirty('is_approved') && !$user->is_approved && $user->getOriginal('is_approved')) {
            $user->approved_at = null;
            $user->approved_by = null;
        }
    }
}
