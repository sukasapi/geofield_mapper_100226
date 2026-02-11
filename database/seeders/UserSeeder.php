<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@geofieldmap.local',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@geofieldmap.local',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@geofieldmap.local',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad@geofieldmap.local',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
