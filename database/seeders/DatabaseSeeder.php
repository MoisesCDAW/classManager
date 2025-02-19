<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        // create departments
        Department::create([
            'name' => 'Informática',
        ]);

        // create departments
        Department::create([
            'name' => 'Ciencias',
        ]);


        // create admin user
        User::create([
            'department_id' => 1,
            'name' => 'Admin',
            'surnames' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('aA1$qwer'),
        ]);

        // create other user
        User::create([
            'department_id' => 1,
            'name' => 'Moisés',
            'surnames' => 'Campos Perdomo',
            'email' => 'moises@gmail.com',
            'password' => Hash::make('aA1$qwer'),
        ]);

        // assign roles to users
        User::find(1)->assignRole('admin');
        User::find(2)->assignRole('professor');


        // Create absences
        // Absence::factory(30)->create();
    }
}
