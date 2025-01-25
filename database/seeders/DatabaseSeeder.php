<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // create departments
        Department::create([
            'name' => 'Dirección',
        ]);


        // create admin user
        User::create([
            'department_id' => 1,
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('aA1$qwer'),
        ]);

        // assign roles to users
        User::find(1)->assignRole('admin');
    }
}
