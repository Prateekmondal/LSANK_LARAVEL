<?php

namespace App\Policies;

use App\Models\Jcr;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JcrPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view JCRs
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Jcr $jcr): bool
    {
        return true; // All authenticated users can view specific JCRs
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Field_Officer') || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Jcr $jcr): bool
    {
        // Super admin can update anything
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin can update anything except approved JCRs
        if ($user->isSuperAdmin() && !$jcr->isApproved()) {
            return true;
        }
        
        // Regular users can only update their own drafts
        return $jcr->isDraft() && $user->id === $jcr->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Jcr $jcr): bool
    {
        // Only allow deletion if JCR is in draft status and user is creator or super-admin
        return $jcr->isDraft() && 
               ($user->id === $jcr->creator_id || $user->hasRole('super-admin'));
    }

    /**
     * Determine whether the user can sign as party chief.
     */
    public function signAsPartyChief(User $user): bool
    {
        return $user->hasRole('party_chief');
    }

    /**
     * Determine whether the user can sign as operation incharge.
     */
    public function signAsOperationIncharge(User $user): bool
    {
        return $user->hasRole('operation_incharge');
    }

    /**
     * Determine whether the user can approve JCRs.
     */
    public function approve(User $user, Jcr $jcr): bool
    {
        return $user->hasRole('operation_incharge') && $jcr->isPendingOperationIncharge();
    }
}