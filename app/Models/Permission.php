<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
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
}