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
        Permission::create(['name' => 'add JCR']);
        Permission::create(['name' => 'view JCR']);
        Permission::create(['name' => 'edit JCR']);
        Permission::create(['name' => 'delete JCR']);
    }
}
