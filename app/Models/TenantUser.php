<?php

namespace App\Models;

class TenantUser extends User
{
    /**
     * TenantUser extends User but has NO explicit $connection = 'central' property.
     * This allows it to dynamically inherit the active tenant connection name (e.g. 'tenant')
     * when tenancy is initialized, ensuring all pivot relationships (like Jcr->users())
     * resolve on the correct tenant database instead of crashing on the central connection.
     */
    protected $connection = null;

    /**
     * Prefix the central database name dynamically to the 'users' table name.
     * Since the users table only resides in the central database, but we are querying it
     * from a tenant database connection, MySQL requires qualifying the table name with
     * the central database name (e.g. 'central_db.users') to perform cross-database joins.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $centralDb = config('database.connections.central.database', 'lsank_laravel');
        $this->table = $centralDb . '.users';
    }
}
