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
        $role = Role::create(['name' => 'Head_Logging_Services']);
        $role->givePermissionTo(['add JCR', 'view JCR', 'edit JCR', 'delete JCR']);
        $role = Role::create(['name' => 'Location Manager']);
        $role->givePermissionTo(['add JCR', 'view JCR', 'edit JCR', 'delete JCR']);
        $role = Role::create(['name' => 'In-Charge_Operation']);
        $role->givePermissionTo(['add JCR', 'view JCR', 'edit JCR']);
        $role = Role::create(['name' => 'Technical_Support_Group']);
        $role->givePermissionTo(['add JCR', 'view JCR']);
        $role = Role::create(['name' => 'Party_Chief']);
        $role->givePermissionTo(['add JCR', 'view JCR']);
        $role = Role::create(['name' => 'Field_Officer']);
        $role->givePermissionTo(['add JCR', 'view JCR']);
    }
}
