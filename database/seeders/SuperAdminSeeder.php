<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['cpf' => 134283],
            [
                'id'            => 20,
                'seniority'     => 23,
                'name'          => 'Prateek Mondal',
                'designation'   => 'Senior Geophysicist(Well)',
                'email'         => 'prateekmondal@gmail.com',
                'phone'         => 9476433227,
                'password'      => 'Testing321#',
                'is_approved'   => true,
                'is_super_admin' => true, // Grants access to the central Filament admin panel
            ]
        );

        // Role assignment is done via: php artisan assign:role 134283 super-admin --all-tenants
    }
}
