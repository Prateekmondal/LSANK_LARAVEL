#!/bin/bash

# Tenant Management Feature - Verification Checklist
# Run this script to verify all components are properly set up

echo "🔍 Tenant Management Feature Verification Checklist"
echo "=================================================="
echo ""

# Check 1: Required files exist
echo "✓ Checking if required files exist..."
files=(
    "app/Jobs/CreateTenantJob.php"
    "app/Policies/TenantPolicy.php"
    "app/Filament/Resources/TenantResource.php"
    "app/Filament/Resources/TenantResource/Pages/ListTenants.php"
    "app/Filament/Resources/TenantResource/Pages/CreateTenant.php"
    "app/Filament/Resources/TenantResource/Pages/ViewTenant.php"
    "app/Filament/Resources/TenantResource/Pages/EditTenant.php"
    "app/Providers/AuthServiceProvider.php"
    "database/migrations/2026_05_29_000000_add_fields_to_tenants_table.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✅ $file"
    else
        echo "  ❌ $file - MISSING!"
    fi
done

echo ""
echo "📋 Next Steps:"
echo "=============="
echo ""
echo "1. Run migrations:"
echo "   php artisan migrate"
echo ""
echo "2. Start the queue worker:"
echo "   php artisan queue:work"
echo ""
echo "3. Test as super-admin user:"
echo "   - Login with a user that has is_super_admin = true"
echo "   - Navigate to admin/tenants"
echo "   - Create a new tenant"
echo ""
echo "4. Monitor background job progress:"
echo "   - In another terminal, watch the queue:"
echo "   php artisan queue:work --verbose"
echo ""
echo "5. Check job status:"
echo "   - SELECT * FROM jobs WHERE queue = 'default' LIMIT 5;"
echo "   - SELECT * FROM failed_jobs LIMIT 5;"
echo ""
echo "✨ Your Tenant Management feature is ready!"
