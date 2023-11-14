<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['fname' => 'Dev', 'lname' => 'Ops', 'email' => 'devops@napollo.net', 'password' => Hash::make('12345678'), 'role' => \Role::Developer, 'profile' => '',]);
        User::create(['fname' => 'Super', 'lname' => 'Admin', 'email' => 'michael@projectlifestory.com', 'password' => Hash::make('Michael@312'), 'role' => \Role::SuperAdmin, 'profile' => '',]);
    }
}
