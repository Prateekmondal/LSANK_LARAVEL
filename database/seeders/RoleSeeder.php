<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached permissions to avoid stale checks/race conditions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define all permissions dynamically checked or used by Filament Shield, Policies and Controllers
        $permissionNames = [
            // JCR Permissions
            'view_any_jcr',
            'view_jcr',
            'create_jcr',
            'update_jcr',
            'delete_jcr',
            'delete_any_jcr',
            'force_delete_jcr',
            'force_delete_any_jcr',
            'restore_jcr',
            'restore_any_jcr',
            'replicate_jcr',
            'reorder_jcr',

            // Time Register Permissions
            'view_any_time::register',
            'view_time::register',
            'create_time::register',
            'update_time::register',
            'delete_time::register',
            'delete_any_time::register',
            'force_delete_time::register',
            'force_delete_any_time::register',
            'restore_time::register',
            'restore_any_time::register',
            'replicate_time::register',
            'reorder_time::register',

            // Explosive Checklist Permissions
            'view_any_explosive::checklist',
            'view_explosive::checklist',
            'create_explosive::checklist',
            'update_explosive::checklist',
            'delete_explosive::checklist',
            'delete_any_explosive::checklist',
            'force_delete_explosive::checklist',
            'force_delete_any_explosive::checklist',
            'restore_explosive::checklist',
            'restore_any_explosive::checklist',
            'replicate_explosive::checklist',
            'reorder_explosive::checklist',

            // Audit Log Permissions
            'view_any_audit::log',
            'view_audit::log',
            'create_audit::log',
            'update_audit::log',
            'delete_audit::log',
            'delete_any_audit::log',
            'force_delete_audit::log',
            'force_delete_any_audit::log',
            'restore_audit::log',
            'restore_any_audit::log',
            'replicate_audit::log',
            'reorder_audit::log',

            // Shield Role Permissions
            'view_any_shield::role',
            'view_shield::role',
            'create_shield::role',
            'update_shield::role',
            'delete_shield::role',
            'delete_any_shield::role',

            // User Permissions
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'delete_any_user',
            'force_delete_user',
            'force_delete_any_user',
            'restore_user',
            'restore_any_user',
            'replicate_user',
            'reorder_user',
        ];

        // 2. Create permissions
        foreach ($permissionNames as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }

        $allPermissions = Permission::all();

        // 3. Setup Super Admin & Head Logging Services (both case formats)
        $superAdminRoles = ['super-admin', 'super_admin'];
        $headLoggingRoles = ['head_logging_services', 'Head_Logging_Services'];
        foreach (array_merge($superAdminRoles, $headLoggingRoles) as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($allPermissions);
        }

        // 4. Setup Location Manager (all permissions except Shield Role management)
        $locationManagerRoles = ['location_manager', 'Location Manager'];
        $locationManagerPermissions = $allPermissions->filter(function ($p) {
            return !str_contains($p->name, 'shield::role');
        });
        foreach ($locationManagerRoles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($locationManagerPermissions);
        }

        // 5. Setup Officers (Field Officer, Party Chief, Operation Incharge)
        $officerRoles = [
            'field_officer',
            'Field_Officer',
            'party_chief',
            'operation_incharge',
            'in_charge_operation'
        ];
        $officerPermissions = $allPermissions->filter(function ($p) {
            return str_contains($p->name, '_jcr') ||
                   str_contains($p->name, '_time::register') ||
                   str_contains($p->name, '_explosive::checklist');
        });
        foreach ($officerRoles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($officerPermissions);
        }

        // 6. Setup Technical Support Group (permissions without deletes)
        $tsgRoles = ['technical_support_group', 'Technical_Support_Group'];
        $tsgPermissions = $allPermissions->filter(function ($p) {
            return (
                str_contains($p->name, '_jcr') ||
                str_contains($p->name, '_time::register') ||
                str_contains($p->name, '_explosive::checklist')
            ) && !str_starts_with($p->name, 'delete');
        });
        foreach ($tsgRoles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($tsgPermissions);
        }

        // 7. Setup Staff & OT Group (read-only permissions)
        $staffRoles = ['field_staff', 'staff', 'ot_group'];
        $staffPermissions = $allPermissions->filter(function ($p) {
            return (
                str_starts_with($p->name, 'view') ||
                str_starts_with($p->name, 'view_any')
            ) && (
                str_contains($p->name, '_jcr') ||
                str_contains($p->name, '_time::register') ||
                str_contains($p->name, '_explosive::checklist')
            );
        });
        foreach ($staffRoles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($staffPermissions);
        }
    }
}
