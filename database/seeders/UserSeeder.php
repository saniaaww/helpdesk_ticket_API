<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Budi (IT Support)',
            'email' => 'budihelp@mail.com',
            'password' => Hash::make('budi123'),
            'role' => 'helpdesk'
        ]);

        User::create([
            'name' => 'Santi (Network Tech)',
            'email' => 'santihelp@mail.com',
            'password' => Hash::make('santi123'),
            'role' => 'helpdesk'
        ]);

        User::create([
            'name' => 'Rian (Hardware)',
            'email' => 'rianhelp@mail.com',
            'password' => Hash::make('rian123'),
            'role' => 'helpdesk'
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);
    }
}