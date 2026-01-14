<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
        $role = Role::create(['name' => 'head_logging_services']);
        // $role->givePermissionTo(['add jcr', 'view jcr', 'edit jcr', 'delete jcr']);
        $role = Role::create(['name' => 'location_manager']);
        // $role->givePermissionTo(['add jcr', 'view jcr', 'edit jcr', 'delete jcr']);
        $role = Role::create(['name' => 'in_charge_operation']);
        // $role->givePermissionTo(['add jcr', 'view jcr', 'edit jcr']);
        $role = Role::create(['name' => 'technical_support_group']);
        $role = Role::create(['name' => 'ot_group']);
        // $role->givePermissionTo(['add jcr', 'view jcr']);
        $role = Role::create(['name' => 'party_chief']);
        // $role->givePermissionTo(['add jcr', 'view jcr']);
        $role = Role::create(['name' => 'field_officer']);
        $role = Role::create(['name' => 'field_staff']);
        // $role->givePermissionTo(['add jcr', 'view jcr']);
    }
}
