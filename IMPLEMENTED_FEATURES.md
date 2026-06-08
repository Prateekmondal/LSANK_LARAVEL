# LSANK Laravel Application - Implemented Features Inventory

## Overview
This is a multi-tenant Laravel application with Filament admin panel built for well logging operations, JCR (Job Completion Report) management, and explosive checklist handling.

---

## 1. Models (app/Models/)

### Core Business Models

| Model | Purpose |
|-------|---------|
| **Jcr** | Main Job Completion Report model tracking well logging operations. Stores extensive well data including field name, well number, job dates, depths, rod properties, fluid properties, and all operational parameters. Uses Spatie permissions. |
| **TimeRegister** | Tracks time records for well operations including indentation, well taken-up, and handover times. Manages logging chief and rig representative signatures. Generates unique signature tokens for external signings. |
| **ExplosiveChecklist** | Pre and post-departure checklists for explosive operations. Supports checklist items with status and comments. Links to external signers and internal signatures. |
| **ChecklistSignature** | Records internal signatures on explosive checklists (creator, approver types). Tracks signed_at timestamp and comments. |
| **ChecklistForward** | Handles forwarding of checklists between users for signatures. Stores from/to user relationships, message, purpose, and is_signed status. |
| **ExternalSignature** | Records external party signatures (non-system users) on explosive checklists with CPF, email, and designation. |
| **logsRecorded** | Records log data for a JCR including run numbers, depths, tool information, log quality, charges, and fuze data. |
| **explosiveUsed** | Tracks explosive inventory usage per JCR: issued, used, returned quantities. |
| **loggingUnit** | Master data for logging unit types. Many-to-many relationship with logTypes. |
| **logType** | Master data for log types. Many-to-many relationship with loggingUnits via loggingUnitType. |
| **loggingUnitType** | Junction table for logging unit and log type relationships. |

### System & Authentication Models

| Model | Purpose |
|-------|---------|
| **User** | Extends Illuminate Authenticatable. Implements FilamentUser, MustVerifyEmail, and HasAvatar. Uses Spatie permission traits. Tracks approval status, seniority, CPF, designation. Central DB connection. |
| **Tenant** | Multi-tenancy model extending Stancl\Tenancy BaseTenant. Implements TenantWithDatabase. Manages tenant databases and domains with is_active status. |
| **TenantUser** | Extended User model for tenant-specific operations. Dynamically inherits tenant connection and prefixes database names for cross-DB joins. |
| **Role** | Custom Spatie role model with dynamic connection resolution based on tenancy state. Handles pivot cleanup for correct tenant DB. |
| **Permission** | Custom Spatie permission model with dynamic connection resolution for central/tenant databases. |

### Data & Administrative Models

| Model | Purpose |
|-------|---------|
| **AuditLog** | Polymorphic audit logging for all model changes. Tracks event (created/updated/deleted), user who performed action, old_values, and new_values as JSON. Tenant connection. |
| **contact** | Simple contact form model. Auto-sends ContactMail when created. |
| **Notification** | System notification model. |

---

## 2. Jobs (app/Jobs/)

| Job | Purpose |
|-----|---------|
| **CreateTenantJob** | Queued job for tenant creation. Initializes tenancy, runs migrations on tenant DB, and executes seeders. Handles async tenant setup. |
| **ProcessNotification** | Queued job for sending notifications. Dispatches checklist approval notifications to users asynchronously. |

---

## 3. Notifications (app/Notifications/)

| Notification | Channel | Purpose |
|--------------|---------|---------|
| **ChecklistApprovalNotification** | Database | Alerts approver that a checklist requires approval. Includes checklist type, well name, date. |
| **ChecklistForwardedNotification** | Database | Notifies recipient that a checklist has been forwarded for signature. Includes forwarding message. |
| **ExternalSignerNotification** | Mail, Database | Requests external party signature on Checklist B. Includes well name, job type, date, and signature link. |
| **JcrAssignedNotification** | Database | Notifies user they've been assigned as Party Chief for a JCR. Includes field name, well number, JCR ID. |
| **UserPendingApprovalNotification** | Mail, Database | Alerts admins of new user registration pending approval with user details and registration timestamp. |
| **UserLocationChangeApprovalNotification** | Mail, Database | Notifies admins of user location/tenant changes requiring approval. Includes previous and new locations. |

---

## 4. Services (app/Services/)

| Service | Purpose |
|---------|---------|
| **SapService** | Integrates with SAP systems. Pushes JCR data to SAP APIs. Validates JCR readiness for push. Retrieves and stores SAP document numbers. Handles SAP API authentication via config/env. |

---

## 5. Observers (app/Observers/)

| Observer | Purpose |
|----------|---------|
| **AuditLogObserver** | Prevents updates/deletes on audit logs (returns false). Ensures immutability of audit records. |
| **UserObserver** | Auto-manages user approval workflow. Sets approved_at and approved_by when is_approved changes from false→true. Clears these when toggled back to false. |

---

## 6. Filament Resources (app/Filament/Resources/)

### Data Management Resources

| Resource | Model | Purpose |
|----------|-------|---------|
| **UserResource** | User | Manage system users: CPF, seniority, name, designation, email, phone, avatar, status, role assignment, approval toggle with auto-timestamp, location/tenant assignment. |
| **JcrResource** | Jcr | Comprehensive JCR creation/editing with wizard UI. Fields organized in steps: basic info (field, well, dates, indent), well details, fluid properties, rod properties, explosive data, charges, and related entities management. |
| **TenantResource** | Tenant | Multi-tenant administration: tenant name, unique ID, domain configuration (repeater), and active status. |
| **TimeRegisterResource** | TimeRegister | Time register form: logging unit, indent, well details, time entries (indented/taken-up/handed-over), job description, chief/representative signatures, status tracking. |
| **ExplosiveChecklistResource** | ExplosiveChecklist | Checklist creation: link to JCR, type, date, repeater for checklist items with status and comments, status, creator, sign statuses. |
| **AuditLogResource** | AuditLog | Read-only audit log viewer with filters by event, user, model type. |
| **ChecklistForwardResource** | ChecklistForward | Manage checklist forwarding between users with messages and purposes. |
| **ChecklistSignatureResource** | ChecklistSignature | View and manage signatures on checklists. |
| **ContactResource** | contact | Manage contact form submissions. |
| **ExplosiveUsedResource** | explosiveUsed | Track explosive inventory usage per JCR. |
| **ExternalSignatureResource** | ExternalSignature | View external party signatures with CPF, designation, email. |
| **LogsRecordedResource** | logsRecorded | Manage log run records with depths, tool info, quality, charges. |
| **LoggingUnitResource** | loggingUnit | Master data for logging units. |
| **LoggingUnitTypeResource** | loggingUnitType | Junction data for logging unit/type relationships. |
| **LogTypeResource** | logType | Master data for log types. |
| **NotificationResource** | Notification | System notification viewer. |

---

## 7. Livewire Components (app/Livewire/)

| Component | Purpose |
|-----------|---------|
| **JobNo** | Dynamic job number generator. Fetches next job number based on selected unit. Queries JCR records to auto-increment job numbers per unit. |

---

## 8. Middleware (app/Http/Middleware/)

| Middleware | Purpose |
|-----------|---------|
| **CheckRole** | Role-based access control. Verifies user has required role; denies access with 403 if unauthorized. |
| **CheckUserApproval** | User approval enforcement. Logs out users with is_approved=false and redirects to login with error message. Prevents unapproved user access. |
| **CheckTimeRegisterCompletion** | JCR-TimeRegister linking enforcement. Redirects to time register linking modal if JCR requires time register but hasn't been linked. |
| **StaffRestriction** | Staff role limitation. Restricts Staff users to only JCR print view. Redirects all other access attempts to print route. |
| **HasRole** | Custom role checking middleware (inherited/extended). |
| **InitializeTenancyByDomainOrSkipForCentral** | Tenancy initialization based on domain. Auto-identifies tenant from request domain or skips for central routes. |

---

## 9. Mail Classes (app/Mail/)

| Mail Class | Purpose | Recipient |
|-----------|---------|-----------|
| **ContactMail** | Contact form submission email. Sends contact form data to admin. View-based template (emails.contact). | Admin |
| **RigSignatureRequest** | Requests rig representative signature on time register. Includes signature URL and time register details. HTML email with action link. | Rig Representative (external) |
| **TimeRegisterSignedCopy** | Sends completed time register as PDF attachment. Generates PDF from time-registers.pdf view. Named file: Job_Carried_Out_Report_{well_no}_{timestamp}.pdf | Logging Chief / Rig Rep |

---

## 10. Traits (app/Traits/)

| Trait | Purpose |
|-------|---------|
| **Auditable** | Automatic audit logging for models. Hooks into created/updated/deleted events. Captures old and new values as JSON. Creates AuditLog records polymorphically with user ID and event type. Used across most business models. |

---

## Database Structure Summary

### Tenant-Specific Tables (Multi-tenant)
- jcr
- timeRegisters
- explosiveChecklists
- checklistSignatures
- checklistForwards
- externalSignatures
- logsRecorded
- explosiveUsed
- loggingUnits
- logTypes
- loggingUnitTypes
- auditLogs
- contacts
- notifications
- roles, permissions, model_has_roles, model_has_permissions (Spatie)

### Central Database Tables
- users
- tenants
- tenant_domains
- failed_jobs
- password_reset_tokens

---

## Key Features & Workflows

### 1. Multi-Tenancy
- Stancl/Tenancy package implementation
- Isolated tenant databases with shared User/Tenant central DB
- Dynamic connection resolution in models
- Domain-based tenant identification

### 2. User Approval Workflow
- New users created in unapproved state
- Admins approve users via toggle in UserResource
- Auto-sets approved_at and approved_by timestamps
- Unapproved users blocked from login

### 3. JCR & Time Register Linking
- Middleware enforces time register linking before JCR access
- JCR requires associated time register
- Prevents incomplete operational records

### 4. Explosive Checklist Management
- Pre and post-departure checklists
- Multiple signature types: creator, approver, external
- Checklist forwarding between internal users
- External party signature request via email

### 5. Audit Trail
- All model changes logged via Auditable trait
- Polymorphic audit logging
- Immutable audit records
- Filters available: by event, user, model type

### 6. SAP Integration
- Push JCR data to external SAP systems
- Document number tracking
- Validation of JCR completeness before push
- Prevents duplicate SAP submissions

### 7. Role-Based Access Control
- Spatie permission/role system
- Custom role checks (CheckRole middleware)
- Staff users limited to JCR print view only
- Dynamic permission resolution (central/tenant)

### 8. Email Notifications
- User approval notifications
- Checklist approval requests
- External signature requests
- Time register signed copy delivery
- Contact form submissions

---

## Dependencies & Integrations

- **Filament**: Admin panel & CRUD interface
- **Spatie Permission**: Role & permission management
- **Stancl Tenancy**: Multi-tenancy package
- **Livewire**: Real-time UI components
- **Barryvdh/DomPDF**: PDF generation
- **Laravel Sanctum**: API token authentication
- **Filament Shield**: Permission integration with Filament

---

## Configuration Files Referenced
- `config/tenancy.php` - Multi-tenancy settings
- `config/filament.php` - Filament admin panel config
- `config/services.php` - SAP service credentials
- `config/permission.php` - Spatie permissions
- `config/database.php` - Central & tenant DB connections

