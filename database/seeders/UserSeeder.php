<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin HKBP',
            'email' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);
    }
}
