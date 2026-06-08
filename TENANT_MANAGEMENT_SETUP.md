# Tenant Management Feature - Implementation Guide

## Overview
This implementation adds a sidebar option in the Filament admin panel to create new tenants. Only super-admin users can access this feature. When creating a new tenant, migrations and seeders automatically run in the background using Laravel's job queue.

## Files Created/Modified

### 1. **Job for Background Processing**
- **File**: `app/Jobs/CreateTenantJob.php`
- **Purpose**: Handles tenant creation, database initialization, migrations, and seeders in the background
- **Features**:
  - Initializes tenancy context for the specific tenant
  - Runs Laravel migrations for the tenant
  - Runs Laravel seeders for the tenant
  - Updates tenant status to active when complete
  - Logs errors if something fails

### 2. **Authorization Policy**
- **File**: `app/Policies/TenantPolicy.php`
- **Purpose**: Controls access to tenant management operations
- **Rules**: Only super-admin users (with `is_super_admin = true`) can view, create, update, or delete tenants

### 3. **Filament Resource**
- **File**: `app/Filament/Resources/TenantResource.php`
- **Purpose**: Provides the Filament admin interface for tenant management
- **Features**:
  - Creates a new sidebar navigation item under "Administration" group
  - Form fields for:
    - Tenant Name (display name for the office)
    - Tenant ID (unique identifier, used for database prefix)
    - Domain configuration (add multiple domains/subdomains)
    - Active status toggle
  - Table view with:
    - Searchable Tenant ID and Name
    - Domain list display
    - Active status indicator
    - Created date
  - Only visible to super-admin users
  - Automatic hiding from navigation if user is not super-admin

### 4. **Filament Pages**
Created under `app/Filament/Resources/TenantResource/Pages/`:

#### **ListTenants.php**
- Lists all tenants in a table
- "Create New Tenant" button in header

#### **CreateTenant.php**
- Form for creating new tenants
- Automatically converts Tenant ID to lowercase
- Dispatches `CreateTenantJob` to background queue
- Shows success message: "Tenant created successfully! Migrations and seeders are running in the background."

#### **ViewTenant.php**
- Displays tenant details
- Actions: Edit and Delete

#### **EditTenant.php**
- Allows editing tenant information
- Delete action available

### 5. **Models & Migrations**

#### **Updated Tenant Model** (`app/Models/Tenant.php`)
- Added fillable properties: `id`, `name`, `is_active`
- Added casting for `is_active` as boolean

#### **New Migration** (`database/migrations/2026_05_29_000000_add_fields_to_tenants_table.php`)
- Adds `name` column to tenants table
- Adds `is_active` boolean column (defaults to false)
- Includes rollback logic

### 6. **Authorization Provider**
- **File**: `app/Providers/AuthServiceProvider.php`
- Registers the `TenantPolicy` for the `Tenant` model

## Setup Instructions

### Step 1: Run Migrations
```bash
php artisan migrate
```

This will:
- Add the `name` and `is_active` fields to the central `tenants` table

### Step 2: Ensure Queue Driver is Configured
The implementation uses Laravel's queue system. Verify your `.env` file:

```env
QUEUE_CONNECTION=database
```

Currently, your project uses the database queue driver, which is perfect for this implementation.

### Step 3: Start Queue Worker (Production)
For background jobs to be processed:

```bash
php artisan queue:work
```

**Development**: You can use `queue:listen` for auto-reload:
```bash
php artisan queue:listen
```

**Note**: In development, if you don't want to run a separate queue worker, you can use:
```bash
php artisan tinker
# Then dispatch the job: CreateTenantJob::dispatch($tenantId)->now();
```

### Step 4: Clear Config Cache
After making changes, clear the config cache:

```bash
php artisan config:cache
php artisan route:cache
```

## How It Works

### User Flow

1. **Super-Admin Login**
   - Only users with `is_super_admin = true` can access the Tenants feature
   - Regular tenant admins cannot see this option

2. **Navigate to Tenants**
   - In Filament admin sidebar, look for "Administration" group
   - Click on "Tenants" option

3. **Create New Tenant**
   - Click "Create New Tenant" button
   - Fill in:
     - **Tenant Name**: e.g., "Ahmedabad Office"
     - **Tenant ID**: e.g., "tenantahmedabad" (lowercase, alphanumeric with hyphens/underscores)
     - **Domains**: Add one or more domains/subdomains (e.g., "ahmedabad.yourdomain.com")
     - **Active**: Leave unchecked (automatically set after migrations complete)
   - Click Save

4. **Background Processing**
   - System creates the tenant in the database
   - Returns success notification
   - Background job starts processing:
     - Creates tenant database
     - Runs all pending migrations
     - Seeds the database
     - Sets tenant as active

5. **Tenant Ready**
   - After job completes, tenant is marked as active
   - Users can now access the new tenant through the configured domains

## Authorization & Security

- **Policy-Based**: Uses Laravel policies for fine-grained access control
- **Super-Admin Only**: 
  - Tenant creation restricted to `is_super_admin = true`
  - Cannot be bypassed by role-based permissions (Filament Shield)
  - Applies at multiple levels:
    - Navigation visibility
    - Resource access
    - Policy enforcement

## Database Structure

### Tenants Table (Central Database)
```
id (string, primary key) - Tenant UUID
name (string, nullable) - Display name
is_active (boolean) - Status flag
created_at (timestamp)
updated_at (timestamp)
data (json, nullable) - Stancl tenancy metadata
```

### Tenant Database
- Created as `tenant_{tenant_id}`
- Automatically initialized with:
  - All pending migrations
  - Database seeders
  - All necessary tables for tenant operations

## Troubleshooting

### Jobs Not Processing
1. Ensure queue worker is running: `php artisan queue:work`
2. Check database migrations were run: `php artisan migrate:status`
3. Check jobs table: `SELECT * FROM jobs;`

### Tenant Not Showing in Sidebar
1. Verify `is_super_admin` is set to `true` for your user
2. Clear cache: `php artisan cache:clear`
3. Refresh browser

### Migration Errors for New Tenant
1. Check logs: `storage/logs/laravel.log`
2. Check failed_jobs table: `SELECT * FROM failed_jobs;`
3. Verify database user has permission to create databases

### Domain Configuration Issues
1. Ensure subdomain is configured in DNS
2. Check Laravel tenancy config: `config/tenancy.php`
3. Verify central domains list to avoid conflicts

## Configuration

### Customizing the Create Job
To add custom logic when creating a tenant, modify `app/Jobs/CreateTenantJob.php`:

```php
public function handle()
{
    // ... existing code ...
    
    // Add custom initialization after seeding
    $tenant->customInitialization();
}
```

### Customizing Seeders
Add custom seeders to your `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    // Seeders run automatically for new tenants
    $this->call([
        UserSeeder::class,
        RoleSeeder::class,
        // ... add your custom seeders
    ]);
}
```

## Next Steps

1. Create tenant-specific seeders in `database/seeders/`
2. Add custom migrations for new tenants
3. Test the feature with a super-admin account
4. Monitor the jobs table to ensure jobs are processing
5. Set up monitoring for queue failures (optional)

## Additional Notes

- **Queue Timeout**: Default timeout is 60 seconds. For larger migrations, adjust in `config/queue.php`
- **Retry Policy**: Jobs are configured to fail after first attempt. Modify `CreateTenantJob` to add retry logic if needed
- **Database Connection**: Tenant databases are created using your configured database driver (MySQL in your case)
- **Naming Convention**: Tenants use UUID format by default (configured in Stancl Tenancy)

---

For questions or issues, check the Stancl Tenancy documentation: https://tenancyforlaravel.com/
