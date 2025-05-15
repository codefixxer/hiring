<?php

// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create a regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'u@u',
            'password' => Hash::make('a'),
            'role' => 'user', 
        ]);

        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'a@a',
            'password' => Hash::make('a'),  // Admin password
            'role' => 'admin',  // Admin role
        ]);


        // Create an agent user
        User::create([
            'name' => 'Agent User',
            'email' => 'a@g',
            'password' => Hash::make('a'),  // Agent password
            'role' => 'agent',  // Agent role
        ]);



        // Create an admin user
        User::create([
            'name' => 'employer User',
            'email' => 'e@e',
            'password' => Hash::make('e'),  // employer password
            'role' => 'employer',  // employer role
        ]);
    }
}
