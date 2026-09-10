<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@abox.com'],
            [
                'name' => 'ABox Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'credits' => 9999,
                'is_vip' => true,
                'vip_expires_at' => now()->addYears(10),
                'avatar' => '/images/voices/ai_male4.svg',
            ]
        );

        // Standard Demo user
        User::updateOrCreate(
            ['email' => 'user@abox.com'],
            [
                'name' => 'Alex Voice',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'credits' => 120,
                'is_vip' => false,
                'vip_expires_at' => null,
                'avatar' => '/images/voices/ai_male2.svg',
            ]
        );
    }
}
