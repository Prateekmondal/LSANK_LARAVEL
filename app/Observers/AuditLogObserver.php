<?php

namespace App\Observers;

use App\Models\AuditLog;

class AuditLogObserver
{
    /**
     * Handle the AuditLog "created" event.
     */
    public function created(AuditLog $auditLog): void
    {
        //
    }

    /**
     * Handle the AuditLog "updated" event.
     */
    public function updated(AuditLog $auditLog): bool
    {
        //
        return false;
    }

    /**
     * Handle the AuditLog "deleted" event.
     */
    public function deleted(AuditLog $auditLog): bool
    {
        //
        return false;
    }

    /**
     * Handle the AuditLog "restored" event.
     */
    public function restored(AuditLog $auditLog): bool
    {
        //
        return false;
    }

    /**
     * Handle the AuditLog "force deleted" event.
     */
    public function forceDeleted(AuditLog $auditLog): bool
    {
        //
        return false;
    }
}
