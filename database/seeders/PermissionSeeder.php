<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create permissions
        Permission::create(['name' => 'add jcr']);
        Permission::create(['name' => 'view jcr']);
        Permission::create(['name' => 'edit jcr']);
        Permission::create(['name' => 'delete jcr']);
    }
}
