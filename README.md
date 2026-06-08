# LSANK - Well Logging & Explosive Operations Management System

<p align="center">
  <strong>A comprehensive Laravel-based platform for managing well logging operations, explosive handling, and regulatory compliance across multiple drilling locations.</strong>
</p>

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [System Architecture](#system-architecture)
4. [Core Features](#core-features)
5. [User Management & Authentication](#user-management--authentication)
6. [Multi-Tenancy](#multi-tenancy)
7. [Main Features](#main-features)
8. [Administration Panel](#administration-panel)
9. [Database & Data Management](#database--data-management)
10. [Integrations](#integrations)
11. [Installation & Setup](#installation--setup)
12. [Configuration](#configuration)
13. [Running the Application](#running-the-application)

---

## 🎯 Project Overview

LSANK is a sophisticated enterprise platform designed for well drilling companies to manage:
- **Well Logging Operations** - Comprehensive Job Card Records (JCRs) with time tracking
- **Explosive Handling** - Inventory management and usage tracking for explosive materials
- **Regulatory Compliance** - Audit trails, approval workflows, and signature management
- **Multi-Location Operations** - Isolated tenant management with shared authentication
- **Personnel & Scheduling** - User management, role-based access, and time registers

The system is deployed across multiple tenants (drilling locations), with each tenant maintaining isolated data while sharing a central authentication and administration layer.

---

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 11.31 with Composer
- **PHP Version**: 8.2+
- **Database**: MySQL
  - Central database for shared data (tenants, super admin users)
  - Tenant-specific databases for operational data

### Frontend
- **Admin Panel**: Filament 3.2 (modern Laravel admin framework)
- **UI Components**: Blade templates with Laravel Vite
- **Styling**: Tailwind CSS 3.4.19
- **JavaScript**: Alpine.js 3.15.12, Axios
- **Date Picker**: Flatpickr

### Key Packages
- **Multi-Tenancy**: Stancl Tenancy 3.10 (SaaS-ready tenancy package)
- **Authorization**: Spatie Laravel Permission 6.10 (role-based access control)
- **Admin Shield**: Filament Shield 3.3 (permission management for Filament)
- **PDF Generation**: Barry vdh Laravel DOMPDF 3.1
- **Authentication**: Laravel Sanctum 4.3
- **Job Queue**: Database-backed job processing

### Development Tools
- **Build Tool**: Vite 6.4.2
- **Testing**: PHPUnit 11.0.1, Faker
- **Code Quality**: Pint (Laravel PHP code style fixer)
- **Debugging**: Tinker, Pail (log viewer)
- **Local Dev**: Laravel Sail

---

## 🏗️ System Architecture

### Multi-Tenancy Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Central Domain                        │
│  (admin.app.com, localhost:8000, 127.0.0.1)            │
│                                                          │
│  • Super Admin Users (is_super_admin = true)           │
│  • Tenant Management                                    │
│  • Global Configuration                                │
│  • Central Database                                    │
└─────────────────────────────────────────────────────────┘
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
   ┌────▼─────┐      ┌──────▼──────┐     ┌────▼─────┐
   │ tenant1  │      │   tenant2   │     │ tenant3  │
   │.app.com  │      │ .app.com    │     │.app.com  │
   │          │      │             │     │          │
   │ DB: TDB1 │      │  DB: TDB2   │     │ DB: TDB3 │
   └──────────┘      └─────────────┘     └──────────┘
```

**Key Points:**
- **Domain-Based Initialization**: Subdomain maps to tenant (e.g., `tenantahmedabad.app.com`)
- **Database Isolation**: Each tenant has separate MySQL database (`tenant_{id}`)
- **Central Authentication**: All users stored in central DB (central connection)
- **Super Admin Access**: Super admins can switch between any tenant using their domain
- **Middleware Stack**: Tenancy middleware activates before Laravel authentication

### Database Connections

```php
// User Model enforces 'central' connection for user authentication
// Tenant-aware models switch dynamically based on tenancy context
'central' => MySQL connection to central database
'tenant'  => MySQL connection to tenant-specific database
```

---

## 🎨 Core Features

### 1. **User Management & Authentication**

#### User Registration & Approval Workflow
- **Self-Registration**: Users can register with CPF, name, email, and password
- **CPF-Based Login**: Custom authentication using CPF (Brazilian tax ID) instead of email
- **Approval System**: 
  - New users created with `is_approved = false`
  - Admin approval required before login
  - Timestamps tracking: `approved_at`, `approved_by` fields
  - Admin roles: Super-admin, Location Manager, Head_Logging_Services

#### User Observer
- **Auto-Timestamp**: Sets `approved_at` when user approved
- **Audit Trail**: Records `approved_by` admin ID
- **Revocation Support**: Clears approval fields when revoked

#### Approval Notifications
- **Email Notifications**: Sent to all admin users on new registration
- **In-App Notifications**: Database notifications with action URLs
- **Queued Processing**: Async notification dispatch via job queue

#### User Model Features
```php
// Key relationships and methods:
- roles() / hasRole()      // Spatie permissions
- approver()               // Relationship to approving admin
- canAccessTenant()        // Tenant access validation
- is_approved              // Boolean approval flag
- is_super_admin           // Super admin bypass
- avatar                   // User profile picture
- email_verified_at        // Email verification
```

### 2. **Role-Based Access Control (RBAC)**

#### Available Roles
- **super-admin**: Full system access, can manage all tenants and users
- **Location Manager**: Manages users and operations at their location
- **Head_Logging_Services**: Oversees logging operations
- **Technical_Support_Group**: Can push data to SAP integration
- **field_officer**: Default role for new users, operational tasks

#### Spatie Permission Integration
```php
// Implemented via spatie/laravel-permission
- Roles per tenant (tenant-scoped permissions)
- Dynamic permissions for CRUD operations
- Guard-based (web, api)
- Permission caching support
```

#### Middleware Protection
- **Role Enforcement**: Validates user roles per route
- **Tenant Scope**: Permissions applied within tenant context
- **Super Admin Bypass**: Super admins bypass Shield restrictions

### 3. **Filament Shield**
- Integrated role & permission management in admin panel
- Fine-grained CRUD permission control (view, create, update, delete)
- Tenant-scoped permissions
- Auto-generated resource permissions
- Admin-only resource access (RoleResource)

---

## 🏢 Multi-Tenancy

### Tenant Model & Management

```php
// app/Models/Tenant.php
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    
    // Fields
    $fillable = ['id', 'name', 'is_active'];
    $casts = ['is_active' => 'boolean'];
    
    // Status
    is_active: boolean (default: false)
    
    // Relationships
    domains() - HasMany relationship to tenant domains
}
```

### Tenant Creation & Initialization

#### Job-Based Tenant Setup
**File**: `app/Jobs/CreateTenantJob.php`
- Triggered automatically when super-admin creates tenant
- **Background Processing**: Runs via database queue
- **Initialization Steps**:
  1. Creates tenant database with `tenant_{id}` naming
  2. Initializes tenancy context for the tenant
  3. Runs all Laravel migrations for the tenant
  4. Seeds initial data via tenant-specific seeders
  5. Sets `is_active = true` on completion
  6. Logs errors if migration/seeding fails

#### Filament Tenant Resource
- **Location**: `app/Filament/Resources/TenantResource.php`
- **Navigation**: "Administration > Tenants" group (super-admin only)
- **CRUD Pages**:
  - **List**: Table view with searchable tenant ID/name, domains, status, creation date
  - **Create**: Form with Tenant Name, ID, Domain configuration
  - **View/Edit**: Full tenant details and modification
  - **Delete**: Soft-delete option (super-admin only)

#### Authorization Policy
**File**: `app/Policies/TenantPolicy.php`
- Only `is_super_admin = true` users can manage tenants
- Gates: viewAny, view, create, update, delete

### Tenant Bootstrap Process

```php
// config/tenancy.php configuration

Bootstrap::class                          // Initialize tenancy
Providers::class                          // Register tenant providers
Database::class                           // Switch to tenant database
Cache::class                              // Tag cache keys with tenant
Filesystem::class                         // Isolate filesystem per tenant
Queue::class                              // Make queue tenant-aware

// Initialization Middleware
InitializeTenancyByDomain::class         // Detect tenant from domain
PreventAccessFromCentralDomains::class   // Block direct access to central
```

### Domain Configuration

```php
// Central Domains (no tenancy)
- localhost
- 127.0.0.1

// Tenant Pattern
- {tenant_id}.app.com (auto-maps to tenant)
- subdomain-based with ID extraction
```

---

## 🎯 Main Features

### 1. **Job Card Records (JCR) Management**

The core operational feature for logging well drilling operations.

#### JCR Model & Fields
```php
// app/Models/Jcr.php

// Core Information
- id: UUID primary key
- well_number: Identifies the well
- field_name: Drilling location/field
- job_date: Date of operation

// Time Tracking
- start_time, end_time: Operation duration
- total_duration: Calculated hours

// Personnel
- party_chief_id: Signing authority
- operation_incharge_id: Operations supervisor
- external_parties: Array of external signatories

// Status & Workflow
- status: pending, approved, signed, sap_pushed
- is_signed_by_party_chief: Boolean
- is_signed_by_operation_incharge: Boolean

// Linked Records
- time_register_id: FK to TimeRegister
- explosive_checkout_id: FK to explosive used
- checklist_id: FK to associated checklist

// SAP Integration
- sap_document_number: Document ID from SAP
- sap_pushed_at: Timestamp of SAP push
- sap_status: pending, pushed, failed
```

#### JCR Workflow
1. **Creation**: User creates JCR with well details
2. **Time Register Linking**: Links to time register for duration tracking
3. **Personnel Assignment**: Assigns party chief and operation incharge
4. **Approval**: Approver reviews and approves JCR
5. **Signing**: Party chief and operation incharge sign JCR
6. **SAP Push**: Technical Support Group pushes to SAP system
7. **Completion**: Archived with document number

#### Authorization Gates
```php
// app/Providers/AuthServiceProvider.php
Gate::define('create_jcr', ...)
Gate::define('edit_jcr', ...)
Gate::define('delete_jcr', ...)
Gate::define('sign_as_party_chief', ...)
Gate::define('sign_as_operation_incharge', ...)
Gate::define('approve_jcr', ...)
Gate::define('push_jcr_to_sap', ...)  // Requires Technical_Support_Group role
```

#### Filament JCR Resource
- **CRUD Interface**: Full management in admin panel
- **Actions**: Create, Edit, View, Delete (with authorization)
- **Filters**: By status, date range, personnel
- **Bulk Actions**: Status updates, bulk signing
- **Notifications**: Auto-notify on status changes

### 2. **Time Register Management**

Tracks daily time worked by personnel and links to JCRs.

```php
// app/Models/TimeRegister.php
- id: UUID
- date: Date of work
- personnel_id: FK to user
- hours_worked: Decimal hours
- status: open, approved, signed
- shift_type: day, night, swing
- location_id: Logging unit reference
- is_signed: Boolean
- signed_at: Timestamp
- linked_jcr_id: FK to JCR

// Relationships
- jcrs() - HasMany linked JCRs
- personnel() - BelongsTo User
```

#### Features
- **Daily Tracking**: Record hours worked per person per day
- **Shift Management**: Support multiple shift types
- **Approval Workflow**: Location managers approve time entries
- **JCR Linking**: Automatic/manual linkage to job card records
- **Calculations**: Auto-calculate duration from linked JCRs

#### Middleware Support
**File**: `app/Http/Middleware/LinkTimeRegisterToJcr.php`
- Auto-associates time registers with JCRs
- Prevents time register modification for staff users
- Maintains audit trail

### 3. **Checklist Management**

Comprehensive safety and compliance checklists for operations.

```php
// app/Models/ChecklistForward.php
- id: UUID
- checklist_type: explosive, safety, equipment
- checklist_data: JSON of checklist items
- status: open, submitted, approved, rejected
- submitted_by_id: FK to user
- submitted_at: Timestamp
- approved_by_id: FK to user
- approved_at: Timestamp
- remarks: Approval remarks
- is_forwarded: Boolean

// app/Models/ChecklistSignature.php
- id: UUID
- checklist_id: FK to ChecklistForward
- signer_id: FK to signer (internal party)
- signed_at: Timestamp
- signature: Signature data
```

#### Checklist Types
1. **Explosive Checklists**: Pre-operation explosive safety verification
2. **Safety Checklists**: General operational safety confirmation
3. **Equipment Checklists**: Equipment readiness verification

#### Signature Workflow
- **Internal Signatures**: Staff members sign within app
- **External Signatures**: Third parties sign via email links
- **Approval Chain**: Supervisors approve checklist submissions
- **Audit Trail**: All signatures timestamped and immutable

#### Features
- **Form Builder**: Dynamic checklist item configuration
- **Forwarding**: Submit checklists for approval
- **External Signing**: Generate and send signature request emails
- **Notification**: Notify relevant parties of pending signatures
- **Tracking**: View all related signatures and approvals

#### External Signature System
**File**: `app/Models/ExternalSignature.php`
- One-time signing links for external parties
- Email integration for signature requests
- PDF generation of signed documents
- Tamper-proof signature verification

### 4. **Explosive Management**

Track explosive materials from inventory to usage.

```php
// app/Models/ExplosiveUsed.php
- id: UUID
- explosive_type: Type of explosive material
- quantity: Amount used
- unit: kg, L, pieces, etc.
- date_used: Date of usage
- jcr_id: FK to associated JCR
- location_id: Logging unit location
- status: checked_out, used, returned
- remarks: Usage notes

// app/Models/ExplosiveChecklist.php
- explosive_checkout_id: FK to usage record
- checked_out_by_id: FK to personnel
- checked_out_at: Timestamp
- return_status: returned, consumed
- return_date: Actual return date
```

#### Workflow
1. **Inventory Check-out**: Personnel check out explosive materials
2. **Checklist Verification**: Safety checklist completed before use
3. **Usage Recording**: Actual usage documented
4. **Return/Consume**: Material returned or marked consumed
5. **Audit Trail**: Complete tracking for compliance

#### Features
- **Inventory Tracking**: Real-time explosive stock management
- **Usage Documentation**: Detailed records of material usage
- **Safety Compliance**: Linked to safety checklists
- **Audit Reports**: Full usage history and compliance reports

### 5. **Audit Logging System**

Complete immutable audit trail of all system changes.

```php
// app/Models/AuditLog.php
- id: UUID
- model_name: Class name of audited model
- model_id: UUID of audited record
- action: created, updated, deleted
- old_values: JSON of previous values
- new_values: JSON of new values
- user_id: FK to user making change
- ip_address: IP of change origin
- user_agent: Browser info
- created_at: Change timestamp

// Trait
HasAuditLog - Applied to models for automatic tracking
```

#### Features
- **Automatic Tracking**: Applied via trait to all major models
- **Immutable Records**: Audit logs cannot be modified/deleted
- **Complete History**: Full before/after values captured
- **User Attribution**: Tracks which user made each change
- **Technical Details**: IP address and browser information
- **Detailed Views**: Filament resource for viewing audit trails

#### Audited Models
- User (registration, approval changes)
- Jcr (all operational changes)
- TimeRegister (time tracking changes)
- ChecklistForward (checklist submissions)
- ExplosiveUsed (explosive tracking)
- And all other transactional models

**File**: `app/Observers/UserObserver.php` - Special handling for user approval tracking

### 6. **Contact & Communication Management**

```php
// app/Models/Contact.php
- id: UUID
- name: Contact name
- email: Email address
- phone: Phone number
- subject: Inquiry subject
- message: Full message
- status: new, responded, closed
- tenant_id: Associated tenant
- created_at, updated_at: Timestamps
```

#### Contact Mail
**File**: `app/Mail/ContactMail.php`
- Sends contact form submissions to admin
- Template-based email with formatted message
- Includes contact details for follow-up

### 7. **Notification System**

Multi-channel notifications for workflow events.

#### Notification Classes
1. **UserPendingApprovalNotification**: New user registration awaiting approval
2. **ChecklistApprovalNotification**: Checklist awaiting approval
3. **ChecklistForwardNotification**: Forwarded checklist notification
4. **ExternalSignatureNotification**: External party signature request
5. **LocationChangeNotification**: User location assignment change

#### Notification Features
- **Multi-Channel**: Email and in-app database notifications
- **Queued Delivery**: Async processing via job queue
- **User-Specific**: Tailored content per recipient
- **Action URLs**: Direct links to relevant admin pages
- **Customizable**: Per-notification type templates

**File**: `app/Notifications/` - All notification classes

#### Notification Model
```php
// Laravel notifications table
- id, notifiable_id, notifiable_type
- type: Notification class name
- data: JSON payload with details
- read_at: Timestamp of read status
- created_at, updated_at
```

---

## 📊 Administration Panel

### Filament Admin Configuration

**Location**: `app/Providers/Filament/AdminPanelProvider.php`

```php
Panel::make()
    ->id('admin')
    ->path('/admin')
    ->login()                          // Custom CPF login
    ->colors(['primary' => Color::Amber])
    ->plugins([ShieldPlugin::make()])   // Filament Shield
    ->middleware([])                   // Tenancy + RBAC middleware
    ->discoverResources()              // Auto-discover resources
    ->discoverPages()                  // Auto-discover pages
    ->discoverWidgets()                // Auto-discover widgets
```

### Sidebar Resources (16 Major Resources)

#### User & Access Management
- **UserResource** (`app/Filament/Resources/UserResource.php`)
  - CRUD for user management
  - Role and permission assignment
  - Approval status toggle
  - Fields: Name, CPF, Email, Password, Roles, Approval status
  
- **Shield/RoleResource** (`app/Filament/Resources/Shield/RoleResource.php`)
  - Manage roles and permissions (super-admin only)
  - Permission matrix editing
  - Tenant-scoped role management

#### Operational Management
- **JcrResource** - Job card records management
- **TimeRegisterResource** - Time tracking records
- **ChecklistForwardResource** - Checklist workflow
- **ChecklistSignatureResource** - Signature management
- **ExplosiveChecklistResource** - Explosive safety checklists
- **ExternalSignatureResource** - External party signatures
- **NotificationResource** - System notifications
- **AuditLogResource** - Change audit trail

#### Configuration
- **LogTypeResource** - Log type configuration
- **LoggingUnitResource** - Drilling location/unit management
- **LoggingUnitTypeResource** - Unit type classification
- **LogsRecordedResource** - Recorded log entries
- **ExplosiveUsedResource** - Explosive usage tracking
- **ContactResource** - Contact message management

#### Tenant Management
- **TenantResource** (`app/Filament/Resources/TenantResource.php`)
  - Super-admin only tenant management
  - Navigation: "Administration > Tenants"
  - Create, Edit, View, Delete tenants
  - Automatic background initialization with migrations/seeders
  - Status toggle and domain configuration

### Admin Dashboard Widgets

- **AccountWidget**: User profile and quick links
- **FilamentInfoWidget**: Filament version and documentation links

### Navigation Auto-Discovery

```php
// Resources automatically appear in sidebar based on:
1. Existence in app/Filament/Resources/
2. User permissions (Shield plugin checks)
3. Navigation registration (can be hidden per resource)
4. Tenant scope (resources filtered by tenant context)
```

### Access Control in Admin

- **Filament Shield**: Fine-grained permission checking
- **Policy Enforcement**: Models have associated policy files
- **Super Admin Bypass**: Super admins bypass most restrictions
- **Tenant Isolation**: Sidebar shows only tenant-relevant resources
- **Role-Based Visibility**: Resources hidden from non-permitted users

---

## 💾 Database & Data Management

### Central Database Tables

```sql
-- Multi-Tenancy
tenants           -- Tenant configurations
tenant_domains    -- Domain mappings for tenants
jobs              -- Queue jobs for background processing
failed_jobs       -- Failed job tracking

-- Authentication & Authorization
users             -- All system users (super-admin + tenant users)
roles             -- Permission roles
permissions       -- Permission definitions
model_has_roles   -- User-role associations
model_has_permissions  -- Role-permission associations
notifications     -- In-app notifications

-- Settings
personal_access_tokens  -- Sanctum API tokens
```

### Per-Tenant Database Tables

```sql
-- Operational Data
jcrs                      -- Job card records
time_registers            -- Time tracking
checklists_forward        -- Checklist submissions
checklist_signatures      -- Checklist signatures
external_signatures       -- External party signatures
explosive_used            -- Explosive usage tracking
logs_recorded             -- Well log data
contacts                  -- Contact inquiries

-- Configuration
logging_units             -- Drilling locations
logging_unit_types        -- Location classifications
log_types                 -- Log data types
explosives               -- Explosive material definitions

-- Audit Trail
audit_logs                -- Change history for all models
```

### Database Naming Convention

```
Central:      laravel_lsank          (or as configured in DB_DATABASE)
Tenant 1:     tenant_tenantid        (auto-named in CreateTenantJob)
Tenant 2:     tenant_anothertenant
```

### Migrations & Seeders

```php
// Tenant-specific locations
database/migrations/       -- Shared migrations (run on central + tenants)
database/seeders/          -- Shared seeders

// Key Migrations
- 2014_10_12_000000_create_users_table
- 2026_02_19_000000_add_approval_fields_to_users_table  -- User approval
- 2026_02_05_000000_add_sap_fields_to_jcrs_table        -- SAP integration
- 2026_05_29_000000_add_fields_to_tenants_table         -- Tenant mgmt
```

### Query Hooks

```php
// Automatic tenant context switching
// Models use 'tenant' connection when tenancy is active
// Super admin users on central connection

// Spatie permission queries scoped to tenant
// Shield permission checks consider tenant context
```

---

## 🔗 Integrations

### 1. **SAP Integration**

Push Job Card Records to external SAP systems.

#### SAP Service
**File**: `app/Services/SapService.php`

```php
class SapService
{
    public function pushJcrToSap(Jcr $jcr): array
    // Validates JCR is ready (approved + signed)
    // Prepares SAP-compatible payload
    // Sends POST request to SAP API
    // Saves document number and timestamp
    // Returns [success => bool, document_number => string, error => string]
}
```

#### JCR SAP Fields
```php
// Added to Jcr model:
- sap_document_number: string, nullable, unique
- sap_pushed_at: timestamp, nullable
- sap_status: string (pending, pushed, failed)

// Helper methods:
- canPushToSap(): bool
- isPushedToSap(): bool
- getSapPushedAtFormatted(): string
```

#### Workflow
1. Approve and sign JCR
2. Technical Support Group user clicks "Push to SAP"
3. SapService validates JCR readiness
4. Prepares well data, personnel, timestamps
5. Sends to SAP via authenticated API endpoint
6. Receives and stores SAP document number
7. Updates JCR status to "sap_pushed"
8. Displays document number on JCR view

#### Authorization
```php
Gate::define('push_jcr_to_sap', function (User $user, Jcr $jcr) {
    return $user->hasRole('Technical_Support_Group');
});
```

### 2. **Email Integration**

Transactional email for notifications, approvals, signatures.

#### Mail Classes
- **ContactMail** - Contact form submissions
- **RigSignatureRequest** - External signature request links
- **TimeRegisterSignedCopy** - Signed time register delivery

#### Configuration
```php
// config/mail.php
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@lsank.app
MAIL_FROM_NAME="LSANK Operations"
```

#### Queue Integration
- All notifications mailable via ShouldQueue
- Async delivery via database queue
- Retry logic for failed sends

---

## 🚀 Installation & Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0+
- Node.js 16+ (for frontend)
- npm or yarn

### Clone & Install

```bash
# Clone repository
git clone <repository-url> lsank
cd lsank

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Database Setup

```bash
# Create MySQL databases
mysql -u root -p
  CREATE DATABASE laravel_lsank;
  CREATE DATABASE tenant_tenantahmedabad;
  CREATE DATABASE tenant_ankleshwar;
  CREATE DATABASE tenant_jorhat;

# Update .env with database credentials
DB_DATABASE=laravel_lsank
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Configuration

```bash
# Generate unique encryption key
php artisan key:generate

# Configure application
# Edit .env file:
APP_NAME="LSANK"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_lsank
DB_USERNAME=root
DB_PASSWORD=password

# Queue
QUEUE_CONNECTION=database

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=...
MAIL_PASSWORD=...

# SAP Integration
SAP_API_ENDPOINT=https://sap.example.com/api/jcr
SAP_API_TOKEN=your_bearer_token
```

### Database Migrations

```bash
# Run migrations on central database
php artisan migrate

# Create first central tenant (central location)
php artisan tinker
  >>> $tenant = App\Models\Tenant::create(['id' => 'tenantahmedabad', 'name' => 'Ahmedabad Location', 'is_active' => true]);
  >>> $tenant->domains()->create(['domain' => 'tenantahmedabad.local']);
```

### Build Frontend Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

---

## ⚙️ Configuration

### Tenancy Configuration

**File**: `config/tenancy.php`

```php
'tenancy' => [
    'db_prefix' => 'tenant_',
    'domain' => 'local',  // Base domain for tenants
    'bootstrap' => [
        // Tenancy initialization order
        \Stancl\Tenancy\Bootstrap\DatabaseManager::class,
        \Stancl\Tenancy\Bootstrap\CacheManager::class,
        \Stancl\Tenancy\Bootstrap\FilesystemManager::class,
        \Stancl\Tenancy\Bootstrap\QueueManager::class,
    ],
]
```

### Permission Configuration

**File**: `config/permission.php`

```php
'permission_model' => \Spatie\Permission\Models\Permission::class,
'role_model' => \Spatie\Permission\Models\Role::class,
'guard_names' => ['web', 'api'],
'permission_cache_key' => 'spatie.permission.cache',
'role_cache_key' => 'spatie.permission.roles_cache',
```

### Filament Shield Configuration

**File**: `config/filament-shield.php`

```php
'super_admin' => [
    'enabled' => true,
    'name' => 'super_admin',
    'define_via_gate' => false,
],
'panel_user' => [
    'enabled' => true,
    'name' => 'default_user',
],
'permission_prefixes' => ['view', 'viewAny', 'create', 'update', 'delete', 'deleteAny', 'restore', 'forceDelete'],
'is_scoped_to_tenant' => true,
```

### Queue Configuration

**File**: `config/queue.php`

```php
'default' => env('QUEUE_CONNECTION', 'database'),
'connections' => [
    'database' => [
        'driver' => 'database',
        'connection' => null,
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 86400,
    ],
],
```

---

## 🏃 Running the Application

### Local Development

```bash
# Terminal 1: Start Laravel development server
php artisan serve
# Runs on http://localhost:8000

# Terminal 2: Start queue worker
php artisan queue:listen --tries=1
# Processes background jobs (tenant creation, notifications)

# Terminal 3: Watch frontend files
npm run dev
# Rebuilds CSS/JS on changes

# Terminal 4: (Optional) Tail logs in real-time
php artisan pail

# Terminal 5: (Optional) Run concurrent processes
npm run dev
```

### Development URLs

```
Web Application:      http://localhost:8000
Admin Panel:          http://localhost:8000/admin
Tenant (Ahmedabad):   http://tenantahmedabad.local:8000
Tenant (Ankleshwar):  http://tenantankleshwar.local:8000
```

### Add Hostnames to /etc/hosts (macOS/Linux)

```
127.0.0.1  localhost
127.0.0.1  tenantahmedabad.local
127.0.0.1  tenantankleshwar.local
127.0.0.1  tenantjorhat.local
```

### First Login

1. Navigate to `http://localhost:8000/admin`
2. Use CPF-based login (ensure user exists in database)
3. Create super-admin user (or use artisan command)

```bash
php artisan tinker
  >>> $user = App\Models\User::create([
    'name' => 'Super Admin',
    'cpf' => '12345678901',
    'email' => 'admin@lsank.app',
    'password' => Hash::make('password'),
    'is_super_admin' => true,
    'is_approved' => true,
  ]);
```

### Testing

```bash
# Run test suite
php artisan test

# With coverage
php artisan test --coverage

# Specific test file
php artisan test tests/Feature/TenantManagementTest.php
```

### Production Deployment

```bash
# Build production assets
npm run build

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start queue worker (daemon mode)
supervisor or nohup php artisan queue:work &

# Setup SSL certificates
php artisan sail:publish

# Configure web server (Nginx/Apache)
# Point document root to /public
# Setup virtual hosts for tenants
```

---

## 📁 Project Structure

```
lsank/
├── app/
│   ├── Console/Commands/          # Artisan commands
│   ├── Filament/
│   │   ├── Admin/                 # Central admin resources
│   │   ├── Pages/                 # Filament pages
│   │   ├── Resources/             # 16+ Filament resources
│   │   └── Widgets/               # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/           # Web controllers
│   │   ├── Middleware/            # Custom middleware
│   │   ├── Requests/              # Form requests
│   │   └── Kernel.php             # HTTP kernel
│   ├── Jobs/
│   │   ├── CreateTenantJob.php    # Tenant initialization
│   │   └── ProcessNotification.php # Notification processing
│   ├── Livewire/                  # Livewire components
│   ├── Mail/                      # Mailable classes
│   ├── Models/                    # 21+ Eloquent models
│   ├── Notifications/             # 6 notification classes
│   ├── Observers/                 # Event observers
│   ├── Policies/                  # Authorization policies
│   ├── Providers/                 # Service providers
│   ├── Services/                  # Business logic services
│   └── Traits/                    # Reusable traits
├── bootstrap/
│   ├── app.php                    # Application bootstrap
│   └── providers.php              # Provider bootstrap
├── config/
│   ├── app.php                    # Application config
│   ├── auth.php                   # Authentication config
│   ├── database.php               # Database connections
│   ├── filament-shield.php        # Shield configuration
│   ├── permission.php             # Spatie permission config
│   ├── tenancy.php                # Tenancy configuration
│   └── ...                        # Other configs
├── database/
│   ├── migrations/                # Database migrations
│   ├── seeders/                   # Database seeders
│   └── factories/                 # Model factories
├── resources/
│   ├── css/                       # Tailwind CSS
│   ├── js/                        # Alpine.js/JavaScript
│   └── views/                     # Blade templates
├── routes/
│   ├── api.php                    # API routes
│   ├── auth.php                   # Authentication routes
│   ├── tenant.php                 # Tenant routes
│   ├── web.php                    # Web routes
│   └── console.php                # Console routes
├── storage/
│   ├── app/                       # File storage
│   ├── logs/                      # Application logs
│   └── tenants/                   # Per-tenant storage
├── tests/
│   ├── Feature/                   # Feature tests
│   ├── Unit/                      # Unit tests
│   └── TestCase.php               # Base test class
├── public/
│   ├── index.php                  # Entry point
│   └── build/                     # Vite build output
├── vendor/                        # Composer packages
├── .env.example                   # Environment template
├── composer.json                  # PHP dependencies
├── package.json                   # Node dependencies
├── vite.config.js                 # Vite configuration
├── tailwind.config.js             # Tailwind configuration
└── README.md                      # Project documentation
```

---

## 🔑 Key Features Summary

| Feature | Status | Description |
|---------|--------|-------------|
| Multi-Tenancy | ✅ | Domain-based tenant isolation with Stancl Tenancy |
| User Authentication | ✅ | CPF-based login with email verification |
| User Approval System | ✅ | Admin approval workflow for new registrations |
| Role-Based Access Control | ✅ | Spatie permissions with 5+ roles |
| Filament Admin Panel | ✅ | Modern admin interface with 16+ resources |
| Job Card Records (JCR) | ✅ | Well logging operations with multi-party signing |
| Time Register Tracking | ✅ | Daily time tracking linked to JCRs |
| Safety Checklists | ✅ | Pre-operation safety verification |
| Explosive Management | ✅ | Explosive inventory check-out and usage tracking |
| External Signatures | ✅ | Email-based signature requests for external parties |
| Audit Logging | ✅ | Complete immutable change history |
| Notifications | ✅ | Multi-channel (email + in-app) event notifications |
| SAP Integration | ✅ | Push JCRs to external SAP system |
| Contact Management | ✅ | Contact form with email notifications |
| File Storage | ✅ | Isolated per-tenant file storage |
| Queue Processing | ✅ | Background job processing (tenant creation, notifications) |

---

## 📧 Support & Documentation

- **Laravel Documentation**: https://laravel.com/docs
- **Filament Documentation**: https://filamentphp.com
- **Spatie Permissions**: https://spatie.be/docs/laravel-permission
- **Stancl Tenancy**: https://tenancyforlaravel.com

---

## 📝 License

This project is proprietary software. All rights reserved.

---

## 👥 Project Contributors

Built for operational well logging and explosive management in drilling operations.

**Last Updated**: June 1, 2026

---

## 🎯 Next Steps & Roadmap

### Potential Enhancements
- [ ] Mobile app for field personnel
- [ ] Advanced reporting and analytics dashboard
- [ ] API documentation with Swagger/OpenAPI
- [ ] Multi-language support (i18n)
- [ ] Enhanced PDF generation and email templates
- [ ] Real-time notifications with WebSockets
- [ ] GPS tracking for field operations
- [ ] Automated compliance reporting
- [ ] Integration with additional third-party systems

---

**This README provides a comprehensive overview of all features implemented in the LSANK system. For detailed technical implementation, refer to the specific feature documentation files in the project root directory.**
