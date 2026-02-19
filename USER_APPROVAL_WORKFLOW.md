# User Approval Workflow Implementation

## Overview
Users can now self-register but can only login after being approved by a super-admin, Location Manager, or Head Logging Service role.

## Components Implemented

### 1. Database Migration
**File**: `database/migrations/2026_02_19_000000_add_approval_fields_to_users_table.php`

Added three columns to users table:
- `is_approved` (boolean, default: false) - Tracks approval status
- `approved_at` (timestamp, nullable) - Records when user was approved
- `approved_by` (unsigned big integer, nullable) - References which admin approved the user

### 2. User Model Updates
**File**: `app/Models/User.php`

**Changes**:
- Added `is_approved`, `approved_at`, `approved_by` to `$fillable` array
- Added casts for `approved_at` (datetime) and `is_approved` (boolean)
- Added `approver()` relationship to get the approving user

### 3. Registration Flow
**File**: `app/Http/Controllers/Auth/RegisteredUserController.php`

**Changes**:
- User registers with CPF, name, email, and password
- New users are created with `is_approved = false`
- Registration success message: "Registration successful! Please wait for approval by an administrator before logging in."
- No auto-login after registration

### 4. Login Authentication Check
**File**: `app/Http/Requests/Auth/LoginRequest.php`

**Changes**:
- Added approval status check in `authenticate()` method
- If user credentials are valid but `is_approved` is false:
  - User is logged out immediately
  - Error message: "Your account is pending approval by an administrator. Please contact support."
- Rate limiting still applies to prevent brute force

### 5. User Approval Observer
**File**: `app/Observers/UserObserver.php`

**Functionality**:
- Automatically sets `approved_at` timestamp when admin toggles `is_approved` from false to true
- Automatically sets `approved_by` to current admin user ID
- Clears both fields if approval is revoked

### 6. Middleware Protection
**File**: `app/Http/Middleware/CheckUserApproval.php`

**Functionality**:
- Checks on every authenticated request if user is approved
- If user is not approved, logs them out
- Redirects to login with error: "Your account is pending approval"

### 7. Route Protection
**File**: `routes/web.php`

**Changes**:
- Applied `check.approval` middleware to all protected routes:
  - JCR operations (`middleware=['auth', 'check.approval']`)
  - Checklists (`middleware=['auth', 'check.approval']`)
  - Time Registers (`middleware=['auth', 'check.approval']`)
- Public routes (registration, login, external signatures) remain unprotected

### 8. Filament User Management
**File**: `app/Filament/Resources/UserResource.php`

**Changes**:
- Added "Approval Status" section with:
  - `is_approved` toggle field - Admins can approve/reject users
  - `approved_at` read-only field - Shows approval timestamp
- Added table columns:
  - `is_approved` icon column (shows checkmark when approved)
  - `approved_at` timestamp column
- Only users with super-admin, Head_Logging_Services, or Location Manager roles can access
- Added filter for approval status

### 9. AppServiceProvider Updates
**File**: `app/Providers/AppServiceProvider.php`

**Changes**:
- Registered `UserObserver` to handle automatic timestamp updates
- UserObserver runs on model updates to set approval timestamps

### 10. HTTP Kernel
**File**: `app/Http/Kernel.php`

**Changes**:
- Registered `check.approval` middleware as route middleware
- Maps to `CheckUserApproval::class`

## Workflow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│ New User Registration                                       │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
        ┌──────────────────────────────────┐
        │ User created with is_approved=0  │
        └──────────────────┬───────────────┘
                           │
                    ┌──────▼──────┐
                    │ Cannot Login │
                    └──────┬──────┘
                           │
         ┌─────────────────────────────────┐
         │ Admin Reviews User in Filament  │
         │ (UserResource > Edit)           │
         └────────────┬────────────────────┘
                      │
         ┌────────────▼────────────────┐
         │ Admin toggles is_approved=1 │
         │ Observer sets:              │
         │ - approved_at = now()       │
         │ - approved_by = admin id    │
         └────────────┬────────────────┘
                      │
         ┌────────────▼─────────────────────┐
         │ User attempts to login           │
         │ LoginRequest checks is_approved  │
         │ ✓ Approved - Login successful    │
         │ ✓ CheckUserApproval middleware   │
         │   allows access to all routes    │
         └────────────┬─────────────────────┘
                      │
                      ▼
        ┌──────────────────────────────┐
        │ User has full access to      │
        │ - JCR management             │
        │ - Time Registers             │
        │ - Checklists                 │
        │ - All protected features     │
        └──────────────────────────────┘
```

## Testing the Workflow

### Test Password Registration → Rejection
1. Register new user at `/register`
2. Try to login - should see: "Your account is pending approval"
3. As admin, go to Filament -> Users -> Find user
4. Toggle `is_approved` OFF and save
5. Error remains: User cannot login

### Test Approval Workflow
1. Register new user
2. As admin in Filament Users resource:
   - Click Edit on pending user
   - Toggle `is_approved` ON
   - Note: `approved_at` auto-populates with current timestamp
   - Note: `approved_by` auto-populates with admin's ID
   - Click Save
3. User can now login successfully
4. User has access to all protected routes
5. If user logout and login again - CheckUserApproval middleware confirms approval status

### Test Approval Revocation
1. Admin finds approved user in Filament
2. Toggle `is_approved` OFF
3. Save changes
4. `approved_at` and `approved_by` are auto-cleared
5. User cannot login anymore

## Security Features

- **Multi-layer Protection**:
  - Registration validation prevents duplicates (CPF uniqueness)
  - Login request checks approval status
  - Route middleware provides continuous verification
  - Observer prevents manual timestamp manipulation

- **Audit Trail**:
  - `approved_at` timestamp shows when approval happened
  - `approved_by` shows which admin approved the user
  - Integrates with existing AuditLog system

- **Role-based Admin Access**:
  - Only super-admin, Location Manager, Head_Logging_Services can manage approvals
  - Filament resource respects role permissions

## DB Schema

```sql
-- Added to users table
ALTER TABLE users ADD COLUMN is_approved BOOLEAN DEFAULT false AFTER status;
ALTER TABLE users ADD COLUMN approved_at TIMESTAMP NULL AFTER is_approved;
ALTER TABLE users ADD COLUMN approved_by UNSIGNED BIGINT NULL AFTER approved_at;
```

## Key Files Modified

1. `database/migrations/2026_02_19_000000_add_approval_fields_to_users_table.php` ✅
2. `app/Models/User.php` ✅
3. `app/Http/Controllers/Auth/RegisteredUserController.php` ✅
4. `app/Http/Requests/Auth/LoginRequest.php` ✅
5. `app/Observers/UserObserver.php` ✅
6. `app/Http/Middleware/CheckUserApproval.php` ✅
7. `app/Http/Kernel.php` ✅
8. `routes/web.php` ✅
9. `app/Filament/Resources/UserResource.php` ✅
10. `app/Providers/AppServiceProvider.php` ✅

## How to Use

### For End Users:
1. Visit registration page and create account
2. Wait for admin approval
3. Once approved, login with CPF and password
4. Access all application features

### For Administrators:
1. Login to Filament admin panel
2. Navigate to Users section
3. Find pending users (Filter by is_approved = false)
4. Click Edit on user
5. Toggle "Approved" switch ON
6. Click Save
7. Timestamp and admin info auto-populate
8. User can now login

### To Reject/Revoke Approval:
1. In Users resource, find user
2. Click Edit
3. Toggle "Approved" switch OFF
4. Click Save
5. User will be logged out and cannot login
