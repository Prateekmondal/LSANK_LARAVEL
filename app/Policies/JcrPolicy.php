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
    // public function viewAny(User $user): bool
    // {
    //     return true; // All authenticated users can view JCRs
    // }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Jcr $jcr): bool
    // {
    //     return true; // All authenticated users can view specific JCRs
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Staff and Technical Support Group cannot create
        if ($user->hasRole('staff') || $user->hasRole('field_staff') || $user->hasRole('Technical_Support_Group') || $user->hasRole('technical_support_group')) {
            return false;
        }

        // Allow typical creators: super-admin, field officer, and head/location roles
        return $user->hasAnyRole([
            'super-admin',
            'Field_Officer',
            'field_officer',
            'Head_Logging_Services',
            'head_logging_services',
            'Location Manager',
            'location_manager',
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Jcr $jcr): bool
    {
        // Super-admin (using roles) can update anything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Party chief and operation incharge may have role-based edit rights
        $canPartyChiefEdit = $user->hasRole('party_chief') && !$jcr->party_chief_edited && $jcr->party_chief_id === $user->id;
        $canOperationInchargeEdit = $user->hasRole('operation_incharge') && !$jcr->operation_incharge_edited;

        // Allow edit when JCR is draft or not finally submitted
        if (($jcr->isDraft() || $jcr->final_submit == 0) && $user->hasRole('Field_Officer')) {
            // Creator can edit their drafts
            // if ($user->id == $jcr->creator_id) {
                return true;
            // }
        }

        // Allow party chief / operation incharge edits when their conditions apply
        if ($canPartyChiefEdit || $canOperationInchargeEdit) {
            return true;
        }

        return false;
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