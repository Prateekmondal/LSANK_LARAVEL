# Filament Admin Panel & Multi-Tenancy Setup Analysis

## 1. Filament Admin Panel Configuration

### Core Setup
- **Location**: `app/Providers/Filament/AdminPanelProvider.php`
- **Panel ID**: `admin`
- **Path**: `/admin`
- **Default Panel**: Yes

### Login Configuration
- **Custom Login**: `app/Filament/Pages/Auth/CpfLogin` 
- **Authentication Method**: CPF (not email)
- **Colors**: Amber primary color

### Key Middleware Stack
```php
// Tenancy Initialization (FIRST)
InitializeTenancyByDomain::class,
PreventAccessFromCentralDomains::class,

// Standard Filament/Laravel middleware
EncryptCookies::class,
AddQueuedCookiesToResponse::class,
StartSession::class,
AuthenticateSession::class,
ShareErrorsFromSession::class,
VerifyCsrfToken::class,
SubstituteBindings::class,
DisableBladeIconComponents::class,
DispatchServingFilamentEvent::class,
```

### Resource Discovery
- **Auto-discovers** resources from: `app/Filament/Resources/`
- **Auto-discovers** pages from: `app/Filament/Pages/`
- **Auto-discovers** widgets from: `app/Filament/Widgets/`

### Default Widgets
- `AccountWidget` - User account widget
- `FilamentInfoWidget` - Filament version info

### Plugins
- **Filament Shield** - Role-based access control

---

## 2. Sidebar/Navigation Structure

### Admin Resources (16 main resources)
Located in `app/Filament/Resources/`:

**User & Access Management:**
- `UserResource.php` - User management with roles/permissions
- `Shield/RoleResource.php` - Role & permission management (super-admin only)

**Operational Resources:**
- `AuditLogResource.php` - Audit trail
- `ChecklistSignatureResource.php` - Checklist signatures
- `ChecklistForwardResource.php` - Checklist forwarding
- `ExplosiveChecklistResource.php` - Explosive checklists
- `ExternalSignatureResource.php` - External signatures
- `NotificationResource.php` - Notifications
- `TimeRegisterResource.php` - Time tracking
- `JcrResource.php` - Main operational resource

**Configuration/Setup Resources:**
- `LogTypeResource.php` - Log types
- `LoggingUnitResource.php` - Logging units
- `LoggingUnitTypeResource.php` - Logging unit types
- `LogsRecordedResource.php` - Recorded logs
- `ExplosiveUsedResource.php` - Explosive inventory
- `ContactResource.php` - Contacts

### Empty Admin Subdirectory
- `app/Filament/Admin/` - Currently empty
- `app/Filament/Admin/Resources/TenantResource/` - Structure exists but no implementation
- `app/Filament/Admin/Resources/UserResource/` - Structure exists but no implementation
- This appears to be reserved for central admin-specific resources

### Navigation Display Logic
- Uses Filament's built-in auto-discovery
- Shield plugin filters visibility based on user permissions
- Resources with `should_register_navigation = false` hidden from sidebar

---

## 3. Multi-Tenancy Configuration

### Tenancy Package & Setup
**Package**: Stancl/Tenancy v3.x (Laravel multi-tenancy)

**Configuration File**: `config/tenancy.php`

### Tenant Model
```php
// app/Models/Tenant.php
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    protected $connection = 'central';
}
```
- **Extends**: Stancl\Tenancy\Database\Models\Tenant
- **Traits**: HasDatabase, HasDomains
- **Connection**: Always uses central DB
- **ID Generator**: UUID
- **Key Fields**: id, data (JSON), created_at, updated_at

### Database Strategy
```
Naming: tenant_{tenant_id}
Example: tenant_ahmedabad, tenant_ankleshwar
```

**Bootstrappers** (activate when tenancy initializes):
1. `DatabaseTenancyBootstrapper` - Switches database connection
2. `CacheTenancyBootstrapper` - Prefixes cache with tenant_id tag
3. `FilesystemTenancyBootstrapper` - Isolates storage
4. `QueueTenancyBootstrapper` - Tenant-aware jobs

**Central Domains** (no tenancy):
- `127.0.0.1`
- `localhost`

### Tenant Domain Binding
- Subdomains map to tenant IDs
- Example: `ahmedabad.localhost` → Tenant ID `ahmedabad`
- Storage: `Stancl\Tenancy\Database\Models\Domain` model

### Multi-Tenancy Events & Jobs
**Tenant Created Event**:
```php
JobPipeline::make([
    Jobs\CreateDatabase::class,
    Jobs\MigrateDatabase::class,
    // Jobs\SeedDatabase::class,  // Disabled
])->shouldBeQueued(false)
```

**Tenant Deleted Event**:
```php
Jobs\DeleteDatabase::class  // Removes tenant DB
```

**Tenancy Lifecycle Events**:
- CreatingTenant, TenantCreated
- UpdatingTenant, TenantUpdated
- DeletingTenant, TenantDeleted
- DomainCreated/Deleted
- TenancyInitialized (fires BootstrapTenancy listener)
- TenancyEnded (fires RevertToCentralContext listener)

### Connection Management
**DatabaseTenancyBootstrapper** Configuration:
```php
'central_connection' => 'central'  // env('DB_CONNECTION', 'central')
'template_tenant_connection' => null
'prefix' => 'tenant_'
'suffix' => ''
'managers' => [
    'mysql' => MySQLDatabaseManager::class,
]
```

---

## 4. User Role System & Super-Admin Permissions

### User Model Overview
**File**: `app/Models/User.php`
**Traits**:
- `HasFactory`
- `Notifiable`
- `HasApiTokens`
- `HasRoles` (Spatie\Permission)
- Uses custom role/permission methods to handle central DB connection

### User Fields & Columns
```php
'tenant_id'         // Assigned tenant (null = accessible from any)
'seniority'         // User seniority level
'cpf'               // Brazilian CPF (national ID)
'name'              // Full name
'designation'       // Job title
'email'             // Email address
'phone'             // Phone number
'description'       // Bio/description (max 255 chars)
'avatar'            // Avatar image path
'status'            // 1 = Active, 0 = Inactive
'is_approved'       // Must be true to login
'is_super_admin'    // Boolean: super-admin flag (CENTRAL DB ONLY)
'approved_at'       // Approval timestamp
'approved_by'       // ID of approver
'email_verified_at' // Email verification
'password'          // Hashed password
```

### Super-Admin System (Dual-Level)

#### 1. Central Super-Admin (Database Boolean)
```php
// User model attribute
'is_super_admin' => true  // Boolean flag in central DB

// Can:
- Access any tenant subdomain
- Bypass all Filament Shield restrictions
- Perform all admin actions
- Manage roles/permissions for all tenants
```

**Checks**:
```php
// AppServiceProvider.php - Gate::before()
$isCentralSuperAdmin = (bool) ($user->is_super_admin ?? false);

// CpfLogin.php - Authentication
if ($user && $user->is_super_admin) {
    // Skip tenant domain restriction
}

// RoleResource.php - Shield admin checks
if ((bool) (auth()->user()?->is_super_admin ?? false)) {
    // Can view/create/update/delete roles
}
```

#### 2. Tenant Super-Admin Role (Spatie Role)
```php
// Tenant database - roles table
Role: 'super_admin' or 'super-admin'

// Checked via:
$isTenantSuperAdmin = method_exists($user, 'hasRole') 
                      && $user->hasRole('super-admin');
```

**Difference**: 
- Central super-admin: Global access, can manage all tenants
- Tenant super-admin: Limited to their tenant, managed via Spatie roles

### Approval Workflow
```
User Created (Status: Pending Approval)
  ↓
Super-admin reviews user
  ↓
Set is_approved = true (required for login)
  ↓
User can now log in (if domain matches for non-super-admin)
  ↓
User authenticated in tenant context
```

**Login Validation** (`CpfLogin.php`):
1. Attempt authentication with CPF + password
2. Check if `is_approved = true` → Reject if false
3. Check if `is_super_admin = true` → Skip domain check
4. Regular user → Verify login domain matches `tenant_id`
5. Session regenerate and proceed

### Role-Based Access Control (Spatie + Shield)

**Configuration**: `config/filament-shield.php`
```php
'super_admin' => [
    'enabled' => true,
    'name' => 'super_admin',
    'define_via_gate' => false,
    'intercept_gate' => 'before',  // Check before Laravel Gate
],

'shield_resource' => [
    'is_scoped_to_tenant' => true,  // Roles are tenant-specific
]
```

**Default Roles** (auto-created):
- `field_officer` - Default role for new users
- `super_admin` - Tenant super-admin role
- `panel_user` - Default panel user role

**Permission Structure** (Prefix-based):
- `view_{resource}` - View resource list
- `view_any_{resource}` - View all records
- `create_{resource}` - Create record
- `update_{resource}` - Edit record
- `delete_{resource}` - Delete single record
- `delete_any_{resource}` - Bulk delete
- `restore_{resource}` - Restore soft-deleted
- `restore_any_{resource}` - Bulk restore
- `force_delete_{resource}` - Permanently delete
- `force_delete_any_{resource}` - Bulk permanent delete

### Permission Storage

**Central Database** (users table):
- `users` table - User records (central only)
- All users login via central

**Tenant Databases** (auto-created by migration):
- `roles` table - Role definitions (tenant-specific)
- `permissions` table - Permission definitions (tenant-specific)
- `model_has_roles` table - User-role assignments
- `model_has_permissions` table - User-permission direct assignments
- `role_has_permissions` table - Role-permission mappings

### Custom Role/Permission Resolution
**Problem**: User model forced to `central` connection, but roles/permissions only exist in tenant DB.

**Solution** (User.php):
```php
// Override roles() relationship
public function roles(): BelongsToMany
{
    if (tenancy()->initialized) {
        // Clone user with tenant connection for pivot queries
        $self = (clone $this)->setConnection(config('database.default'));
        return $self->morphToMany(Role::class, 'model', ...);
    }
    // Central domain: return safe empty relation
    return $this->morphToMany(...)->whereRaw('1 = 0');
}

// Similar override for permissions()
```

**TenantUser Model** (Helper class):
```php
class TenantUser extends User
{
    protected $connection = null;  // Inherits active tenant connection
    
    // Cross-DB join: lsank_laravel.users (central table)
    public function __construct(array $attributes = [])
    {
        $centralDb = config('database.connections.central.database');
        $this->table = $centralDb . '.users';
    }
}
```

### Authorization Gates

**Global Gate Before** (`AppServiceProvider.php`):
```php
Gate::before(function ($user, $ability) {
    $isCentralSuperAdmin = (bool) ($user->is_super_admin ?? false);
    $isTenantSuperAdmin = $user->hasRole('super-admin');
    $isSuperAdmin = $isCentralSuperAdmin || $isTenantSuperAdmin;

    // DELETE abilities restricted to super-admin
    if (in_array($ability, ['delete', 'deleteAny'])) {
        return $isSuperAdmin ? null : false;
    }

    // All other abilities granted to super-admin
    if ($isSuperAdmin) {
        return true;
    }

    return null;  // Fall through to policy checks
});
```

**Custom JCR Gates**:
- `create_jcr`, `edit_jcr`, `delete_jcr`
- `sign_as_party_chief`, `sign_as_operation_incharge`
- `approve_jcr`
- `push_jcr_to_sap` - Requires `Technical_Support_Group` role

### Default User Role on Create
```php
// User.php - boot() method
static::created(function ($user) {
    if (tenancy()->initialized) {
        try {
            $user->assignRole('field_officer');
        } catch (\Throwable $e) {
            // Silently ignore if roles table not available
        }
    }
});
```

---

## 5. Tenant-Related Models

### Model Hierarchy

**Central Database Models**:
- `Tenant` - Multi-tenant instances
  - Has `domains` (HasMany)
  - Fields: id (UUID), data (JSON)
  
- `User` - User accounts (central-only)
  - `tenant_id` relationship → Tenant model
  - `is_super_admin` boolean flag

- `Domain` - Subdomain mappings (Stancl provided)
  - Maps domain strings to tenant IDs
  - Example: `ahmedabad.localhost` → Tenant `ahmedabad`

**Tenant Database Models** (one set per tenant DB):
- `Role` - Spatie role definitions
- `Permission` - Spatie permission definitions
- All operational data models:
  - `Jcr`, `JcrUser`
  - `TimeRegister`
  - `ExplosiveChecklist`
  - `AuditLog`
  - `Notification`
  - etc.

### TenantUser Model
```php
// app/Models/TenantUser.php
class TenantUser extends User
{
    protected $connection = null;  // Dynamic connection
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $centralDb = config('database.connections.central.database');
        $this->table = $centralDb . '.users';  // Cross-DB join
    }
}
```
**Purpose**: Access central users from tenant DB context

### TenantPivot Model
```php
// app/Models/TenantPivot.php
class TenantPivot extends Pivot
{
    // No explicit connection - inherits active tenant connection
}
```
**Purpose**: Pivot tables in tenant DB

### Connection Resolution Logic
```php
// Role.php and Permission.php
public function getConnectionName()
{
    if (tenancy()->initialized) {
        return config('database.default');  // tenant
    }

    if (app()->runningInConsole()) {
        return 'central';
    }

    try {
        $host = request()->getHost();
        $centralDomains = config('tenancy.central_domains');
        if ($host && !in_array($host, $centralDomains)) {
            return 'tenant';  // Subdomain = tenant context
        }
    } catch (\Throwable $e) {
        // Fallback
    }

    return 'central';
}
```

---

## 6. Queue & Jobs System

### Queue Configuration
**File**: `config/queue.php`

**Default Driver**: Database (`env('QUEUE_CONNECTION', 'database')`)

**Database Driver Setup**:
```php
'database' => [
    'driver' => 'database',
    'connection' => env('DB_QUEUE_CONNECTION'),  // Uses main DB connection
    'table' => env('DB_QUEUE_TABLE', 'jobs'),     // jobs table
    'queue' => env('DB_QUEUE', 'default'),
    'retry_after' => 90 seconds,
    'after_commit' => false,
],
```

**Jobs Table**: `jobs` (central or tenant, depending on context)
**Structure**: Standard Laravel jobs table
- `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`

### Job Processing
**Queue Worker**: `php artisan queue:work`
**Worker Daemons**: Can be managed by Supervisor

### Current Jobs

#### ProcessNotification Job
**File**: `app/Jobs/ProcessNotification.php`
**Purpose**: Send queued notifications

```php
class ProcessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $checklist;

    public function __construct(User $user, ExplosiveChecklist $checklist)
    {
        $this->user = $user;
        $this->checklist = $checklist;
    }

    public function handle()
    {
        $this->user->notify(
            new ChecklistApprovalNotification($this->checklist)
        );
    }
}
```

**Usage**:
```php
ProcessNotification::dispatch($user, $checklist);
```

**Notification**: `ChecklistApprovalNotification` (mailable)

### Tenant-Aware Queue Processing

**Middleware**: `QueueTenancyBootstrapper` (auto-loaded)

**Benefits**:
- Jobs executed in correct tenant context
- Database queries target tenant DB automatically
- Cache operations use tenant tag
- Filesystem operations use tenant path

### Job Serializtion & Tenancy
Jobs that dispatch across tenant boundaries:
- `SerializesModels` trait ensures model IDs are serialized
- When job executes, model data re-hydrated in correct tenant context
- Central models (User) always resolve from central DB

---

## Database Structure Overview

### Central Database (lsank_laravel)
```
users               → User accounts (CPF-based login)
tenants             → Multi-tenant instances
domains             → Subdomain → Tenant ID mappings
jobs                → Queued jobs (database driver)
jobs_batches        → Job batch tracking
migrations          → Migration history
password_reset_tokens
sessions
audit_logs          → Audit trail (maybe tenant-scoped?)
```

### Tenant Databases (tenant_{id})
```
roles               → Spatie roles
permissions         → Spatie permissions
model_has_roles     → User-role assignments
model_has_permissions → User-permission assignments
role_has_permissions → Role-permission mappings

jcr                 → Main operational records
jcruser             → JCR users/parties
time_registers      → Time tracking
explosive_checklists → Safety checklists
audit_logs          → Tenant audit logs
notifications       → Notification records
contacts            → Contact management
logging_units       → Unit definitions
logging_unit_types  → Unit type classifications
logs_recorded       → Log entries
log_types           → Log type definitions
explosives_used     → Explosive tracking
checklist_signatures → Signature records
checklist_forwards  → Checklist forwarding records
external_signatures → External party signatures
```

---

## Key Architecture Decisions

1. **Central DB for Users**: All users stored centrally, enables single login point
2. **Tenant DB for Operations**: Business data isolated per tenant, enforces data separation
3. **Connection Switching**: Middleware switches connection based on subdomain
4. **Role Inheritance**: User model overrides to handle cross-DB role/permission access
5. **Super-Admin Bypass**: Boolean flag enables quick access check without DB query
6. **Domain Restriction**: Regular users login restricted to their assigned tenant domain
7. **Queue Tenancy**: Jobs automatically execute in tenant context

---

## File Location Summary

```
app/
├── Filament/
│   ├── Admin/                           # Admin-specific (reserved, empty)
│   ├── Pages/
│   │   └── Auth/CpfLogin.php            # Custom CPF login
│   ├── Resources/                       # 16 resource controllers
│   │   ├── UserResource.php
│   │   ├── Shield/RoleResource.php
│   │   ├── ExplosiveChecklistResource.php
│   │   └── ...
│   └── Widgets/                         # Dashboard widgets
│
├── Models/
│   ├── User.php                         # Central user model
│   ├── Tenant.php                       # Tenancy model
│   ├── TenantUser.php                   # Cross-DB helper
│   ├── TenantPivot.php                  # Tenant pivots
│   ├── Role.php                         # Spatie role override
│   ├── Permission.php                   # Spatie permission override
│   └── ... (16+ operational models)
│
├── Providers/
│   ├── Filament/
│   │   └── AdminPanelProvider.php       # Filament config
│   ├── AppServiceProvider.php           # Auth gates
│   └── TenancyServiceProvider.php       # Tenancy events
│
└── Jobs/
    └── ProcessNotification.php          # Queue job example

config/
├── tenancy.php                          # Tenancy configuration
├── permission.php                       # Spatie permission config
├── filament-shield.php                  # Shield plugin config
└── queue.php                            # Queue driver config
```

---

## Important Notes

### Current Limitations/TODOs
1. `app/Filament/Admin/` directories created but empty - reserved for future admin-specific UI
2. TenantResource/UserResource pages folders exist but no implementations
3. Database seeding commented out in tenancy events
4. Some resources may lack full CRUD implementations

### Best Practices Observed
1. ✅ Connection management abstracted in models
2. ✅ Super-admin roles defined at multiple levels
3. ✅ Approval workflow prevents unauthorized access
4. ✅ Tenant isolation enforced via middleware
5. ✅ Audit logging implemented
6. ✅ Custom authentication with domain validation

### Security Considerations
1. Users must be approved before access
2. Non-super-admin users restricted to assigned tenant domain
3. Delete operations restricted to super-admins only
4. Permission system extends across all resources
5. CPF used instead of email (Brazilian-specific)

