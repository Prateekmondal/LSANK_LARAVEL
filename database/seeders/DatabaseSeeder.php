<?php

namespace Database\Seeders;

use App\Models\Jcr;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);

        // $this->call(SuperAdminSeeder::class);
        // User::factory(10)->create();
        // $user->assignRole('Field Officer');

        // $this->call(JcrSeeder::class);
    }
}
