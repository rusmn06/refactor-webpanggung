<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // demo akun admin
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin2026'),
            'role'     => 'admin',
        ]);

        // demo akun user
        User::create([
            'name'     => 'User Demo',
            'username' => 'user',
            'password' => Hash::make('user2026'),
            'role'     => 'user',
        ]);
    }
}
