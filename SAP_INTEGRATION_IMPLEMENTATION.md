# SAP Integration for JCR - Implementation Summary

## Overview
Implemented the SAP push functionality allowing Technical Support Group to push approved and signed JCRs to SAP, receiving a document number that is saved and displayed on the JCR view page.

## Components Implemented

### 1. Database Migration
**File:** `/database/migrations/2026_02_05_000000_add_sap_fields_to_jcrs_table.php`

Added three new columns to the `jcr` table:
- `sap_document_number` (string, nullable, unique) - Stores the SAP document number returned by SAP
- `sap_pushed_at` (timestamp, nullable) - Records when the JCR was pushed to SAP
- `sap_status` (string, default='pending') - Tracks the status of SAP push (pending, pushed, failed)

### 2. Model Updates
**File:** `/app/Models/Jcr.php`

#### Added Fields to Fillable
```php
'sap_document_number',
'sap_pushed_at',
'sap_status',
```

#### Added to Casts
```php
'sap_pushed_at' => 'datetime',
```

#### New Helper Methods
- `canPushToSap()` - Checks if JCR is approved and signed by Operation Incharge, not yet pushed
- `isPushedToSap()` - Checks if SAP document number exists
- `getSapPushedAtFormatted()` - Returns formatted push timestamp (d-m-Y H:i:s)

### 3. SAP Service Class
**File:** `/app/Services/SapService.php`

Comprehensive service for SAP integration:

#### Main Method: `pushJcrToSap(Jcr $jcr): array`
- Validates JCR is ready for SAP push
- Checks if already pushed
- Prepares SAP payload
- Sends to SAP API
- Saves document number and timestamp on success
- Logs all operations

#### Features:
- **Payload Preparation:** Converts JCR data to SAP-compatible format including:
  - JCR metadata (well number, field name, job details)
  - All time information
  - Personnel information
  - Signature details
  - JCR status and remarks

- **SAP Communication:** 
  - Sends POST request to SAP API endpoint
  - Includes Bearer token authentication
  - 30-second timeout
  - Handles HTTP errors gracefully

- **Mock Mode:** 
  - If `SAP_API_URL` is not configured, generates mock SAP document numbers
  - Format: `SAP-YYYYMMDD-XXXXX` (e.g., `SAP-20260205-A7B2C`)
  - Useful for development and testing

### 4. Controller Method
**File:** `/app/Http/Controllers/JcrController.php`

#### New Method: `pushToSap(Request $request, Jcr $jcr)`
- Verifies user has `Technical_Support_Group` role
- Validates JCR can be pushed
- Calls SAP service
- Returns success/error message
- Redirects back to JCR view with message

### 5. Routes
**File:** `/routes/web.php`

Added new route:
```php
Route::post('jcr/{jcr}/push-to-sap', [JcrController::class, 'pushToSap'])
    ->name('jcr.push-to-sap')
    ->middleware('can:push_jcr_to_sap');
```

### 6. View Updates
**File:** `/resources/views/jcr/show.blade.php`

#### Push to SAP Button
- Shows only when:
  - JCR is approved
  - Operation Incharge has signed
  - User is in Technical Support Group role
  - JCR not yet pushed to SAP
- Includes confirmation dialog
- Styled with success button (green)

#### SAP Information Display
- Shows when JCR has been pushed to SAP
- Displays:
  - SAP Document Number (in success badge)
  - Date and time of push (formatted as d-m-Y H:i:s)
- Styled in info alert box

## Usage Flow

1. **JCR Created and Signed:**
   - Creator fills and signs JCR
   - Party Chief signs
   - Operation Incharge approves

2. **Push to SAP:**
   - Technical Support Group user views JCR
   - Clicks "Push to SAP" button
   - System sends data to SAP
   - SAP returns document number
   - Document number saved to JCR

3. **Display:**
   - SAP document number and push timestamp shown on JCR view page
   - Button disappears after successful push

## Configuration

### Environment Variables Required (Optional)
Add to `.env` if using real SAP integration:

```env
SAP_API_URL=https://your-sap-instance.com/api
SAP_API_KEY=your-api-key-here
```

If not configured, the system uses mock document number generation (useful for development).

## Permission/Role Requirements

- **Technical Support Group** role required to push JCR to SAP
- Add this permission in Filament Shield or through database
- Policy middleware: `can:push_jcr_to_sap`

## Error Handling

The implementation includes robust error handling:
- Validates JCR status before push
- Checks if already pushed (prevents duplicates)
- Handles SAP API errors
- Graceful fallback to mock mode if API not configured
- Logs all operations for auditing
- Returns user-friendly error messages

## Testing

To test the implementation:

1. **Run Migration:**
   ```bash
   php artisan migrate
   ```

2. **Create Test JCR:**
   - Create a new JCR
   - Assign to Party Chief
   - Party Chief signs
   - Operation Incharge approves

3. **Push to SAP:**
   - Login as Technical Support Group user
   - View the approved JCR
   - Click "Push to SAP" button
   - Confirm dialog
   - See success message with document number

4. **Verify:**
   - SAP document number displays on JCR view
   - Push timestamp shows
   - Button no longer appears
   - Database updated with SAP data

## Future Enhancements

- Add retry mechanism for failed pushes
- Implement webhook to receive SAP confirmation
- Add push history/log for each JCR
- Email notifications to stakeholders on push
- Integration with SAP change orders/updates
- Batch push capability for multiple JCRs
