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
        $user = User::create([
            'id' => 20,
            'seniority' => 23,
            'cpf' => 134283,
            'name' => 'Prateek Mondal',
            'designation' => 'Senior Geophysicist(Well)',
            'email' => 'prateekmondal@gmail.com',
            'phone' => 9476433227,
            'password' => 'Testing321#',
            'is_approved' => true,
        ]);

        // $user = User::get()->where('cpf','=', 134283);

        $user->assignRole('super-admin');
    }
}
