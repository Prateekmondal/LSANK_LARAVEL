# Admin Notification on User Registration

## Overview
When a new user registers, all admins with roles **super-admin**, **Location Manager**, or **Head_Logging_Services** are automatically notified via email and database notifications.

## Components Implemented

### 1. UserPendingApprovalNotification Class
**File**: `app/Notifications/UserPendingApprovalNotification.php`

**Features**:
- Implements `ShouldQueue` for async processing
- Sends via two channels:
  - **Mail**: Email notification with user details
  - **Database**: In-app notification stored in `notifications` table
- Contains new user information:
  - Name, Email, CPF
  - Registration timestamp
  - Direct link to user approval in Filament admin

**Mail Content**:
- Subject: "New User Registration - Approval Required"
- Body includes:
  - User's full name, email, and CPF
  - Registration date/time
  - Action button linking to Users resource in Filament
  - Call to action to approve/reject the user

**Database Notification Data**:
```json
{
  "type": "user_pending_approval",
  "user_id": 1,
  "user_name": "New User",
  "user_email": "newuser@example.com",
  "user_cpf": "12345678901",
  "message": "New user New User (newuser@example.com) is pending approval.",
  "action_url": "admin/resources/users"
}
```

### 2. Updated RegisteredUserController
**File**: `app/Http/Controllers/Auth/RegisteredUserController.php`

**Changes**:
- Added import: `use App\Notifications\UserPendingApprovalNotification;`
- After user creation and event dispatch:
  ```php
  $admins = User::role(['super-admin', 'Location Manager', 'Head_Logging_Services'])->get();
  foreach ($admins as $admin) {
      $admin->notify(new UserPendingApprovalNotification($user));
  }
  ```
- Queries all users with admin roles
- Sends notification to each admin asynchronously

### 3. Database Support
**Notifications Table**: `notifications`
- Created via `php artisan notifications:table`
- Columns:
  - `id` (UUID) - Primary key
  - `type` (string) - Notification type: "user_pending_approval"
  - `notifiable_type` (string) - Model class: "App\Models\User"
  - `notifiable_id` (bigint) - Admin user ID
  - `data` (text JSON) - Notification payload
  - `read_at` (timestamp) - When admin read the notification
  - `created_at`, `updated_at` - Timestamps

### 4. Mail Configuration
**File**: `.env`
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=prateekmondal@gmail.com
MAIL_PASSWORD=bpzydvjutfpwiafm
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="prateekmondal@gmail.com"
MAIL_FROM_NAME="LSANK"
```

Gmail SMTP is configured to send emails from the application.

### 5. Queue Configuration
The notification uses `ShouldQueue` interface, meaning:
- Notifications are processed asynchronously
- Sent via Laravel queue system
- Current queue driver (from config/queue.php): Check `.env` for `QUEUE_CONNECTION`
- Prevents blocking user registration response

To process queued jobs manually during development:
```bash
php artisan queue:work
```

## Notification Flow Diagram

```
┌──────────────────────────────────┐
│ New User Registration            │
│ (Registration Form Submitted)    │
└────────────┬─────────────────────┘
             │
             ▼
┌──────────────────────────────────┐
│ RegisteredUserController::store()│
│ - Validate input                 │
│ - Create User (is_approved=0)    │
│ - Fire Registered event          │
└────────────┬─────────────────────┘
             │
             ▼
┌──────────────────────────────────────────┐
│ Query Admins with Roles:                 │
│ - super-admin                            │
│ - Location Manager                       │
│ - Head_Logging_Services                  │
└────────────┬─────────────────────────────┘
             │
             ▼
    ┌────────────────────┐
    │ For Each Admin:    │
    │ notify()           │
    └────────┬───────────┘
             │
             ▼
    ┌────────────────────────────────────┐
    │ UserPendingApprovalNotification    │
    │ - ShouldQueue (async)              │
    │ - via(['mail', 'database'])        │
    └────┬─────────────────┬────────────┘
         │                 │
         ▼                 ▼
    ┌─────────────┐   ┌─────────────────┐
    │ EMAIL SENT  │   │ DATABASE ENTRY  │
    │ SMTP/Gmail  │   │ notifications   │
    │ Admin inbox │   │ table           │
    └─────────────┘   └─────────────────┘
         │                 │
         ▼                 ▼
    Admin receives     Admin sees in
    email with        Filament
    user details      notification
                      bell icon
```

## Workflow Example

### Step 1: New User Registers
```
User fills registration form:
- CPF: 12345678901
- Name: John Doe
- Email: john@example.com
- Password: secret123
```

### Step 2: System Processes Registration
```
✓ Validates input
✓ Creates user with is_approved = false
✓ Fires Registered event
✓ Queries admin users (3 admins found)
```

### Step 3: Admin Notifications Queued
```
For each admin:
- Super Admin (admin1@example.com)
- Location Manager (manager1@example.com)
- Head Logging Service (head1@example.com)

UserPendingApprovalNotification queued
```

### Step 4: Async Queue Processing
```
Queue worker executes notifications:

Email sent to admin1@example.com:
  Subject: New User Registration - Approval Required
  Body: User details and approval link
  
Database notification created for admin1:
  type: user_pending_approval
  data: {user_name: John Doe, user_email: john@example.com, ...}
  
(Repeated for all admins)
```

### Step 5: Admin Actions
```
Admin 1:
- Receives email with "Review & Approve User" button
- OR sees bell icon with new notification in Filament
- Clicks to navigate to Users resource
- Edits user and toggles is_approved = true
- User can now login
```

## Testing the Feature

### Test 1: Email Reception
1. Register new user on application
2. Check recipient email inbox for "New User Registration" email
3. Verify email contains:
   - User name, email, CPF
   - Registration timestamp
   - "Review & Approve User" button link

### Test 2: Database Notification
1. Register new user
2. Check database:
   ```sql
   SELECT * FROM notifications WHERE type = 'user_pending_approval' ORDER BY created_at DESC LIMIT 1;
   ```
3. Verify notification is created for each admin
4. Check `data` JSON field contains user details

### Test 3: Filament Notification Bell
1. Login as admin (super-admin role)
2. Look for notification bell icon in Filament top bar
3. Click bell → see "New user Xxx pending approval" message
4. Can mark as read or click to go to Users resource

### Test 4: Role-Based Notification
1. Create test user with only "Field_Officer" role (not admin)
2. Register new user
3. Test user should NOT receive notification
4. Only users with admin roles receive notification

## Manual Testing Commands

### Test sending notification manually:
```bash
php artisan tinker

# Assuming user ID 1 is a new unapproved user and ID 2 is a super-admin
$newUser = User::find(1);
$admin = User::find(2);
$admin->notify(new \App\Notifications\UserPendingApprovalNotification($newUser));

# To view database notifications:
DB::table('notifications')->where('type', 'user_pending_approval')->get();
```

### Process queued jobs:
```bash
# Run queue worker (processes jobs as they arrive)
php artisan queue:work

# Or process specific number of jobs
php artisan queue:work --tries=3 --timeout=90
```

## Configuration Notes

**Current Configuration**:
- Mail Driver: SMTP (Gmail)
- Queue Connection: Verify in `.env` (usually 'database' or 'redis')
- Notifications Delivery: Mail + Database

**If Using Log Driver** (development):
- Notifications logged to `storage/logs/laravel.log` instead of email
- Set `MAIL_MAILER=log` in `.env` to test without sending real emails

**For Production**:
- Configure proper emails (company email, SendGrid, AWS SES, etc.)
- Set `QUEUE_CONNECTION` to proper queue (Redis preferred for scale)
- Set `APP_ENV=production` 
- Enable queue supervisor/worker (Supervisor, Laravel Horizon, etc.)

## Files Modified

1. ✅ `app/Notifications/UserPendingApprovalNotification.php` - Created
2. ✅ `app/Http/Controllers/Auth/RegisteredUserController.php` - Updated
3. ✅ `database/migrations/yyyy_mm_dd_hhmmss_create_notifications_table.php` - Auto-generated and migrated
4. ✅ `config/mail.php` - Already configured
5. ✅ `app/Models/User.php` - Already has Notifiable trait

## Key Features

✅ **Multi-channel Delivery**: Admins get both email and in-app notifications
✅ **Async Processing**: Notifications queued to not block registration
✅ **Role-based**: Only admins with specific roles notified
✅ **Detailed Info**: Full user details included in notification
✅ **Action Links**: Direct link to approval page in Filament
✅ **Database Record**: Persistent notification history for audit trail
✅ **Gmail Integration**: Uses configured Gmail SMTP credentials

## What Happens When Admin Approves User

1. Admin receives notification email with user details
2. Admin clicks "Review & Approve User" button
3. Redirected to Filament Users resource
4. Finds pending user and clicks Edit
5. Toggles "Approved" switch ON
6. Saves changes
7. `UserObserver` auto-populates `approved_at` and `approved_by`
8. User can now login successfully
9. Admin stays notified via database notification until read

## Summary

The notification system ensures that:
- ✅ New registrations don't go unnoticed
- ✅ **All relevant admins are informed** (super-admin, Location Manager, Head_Logging_Services)
- ✅ Admins can **quickly approve users** via email or Filament dashboard
- ✅ **Complete audit trail** of who approved and when
- ✅ **Non-blocking** - Registration completes instantly, notifications sent async
- ✅ **Scalable** - Queue-based processing handles high registration volume
