<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantPivot extends Pivot
{
    // Do NOT set a $connection property here.
    // By leaving it blank, Laravel will default to the active tenant connection
    // whenever this pivot is queried within a tenant's subdomain.
}