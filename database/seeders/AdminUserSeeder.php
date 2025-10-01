<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    \App\Models\User::updateOrCreate(
        ['email' => 'admin@dinsos.batu.go.id'],
        [
            'name' => 'Admin Utama',
            'password' => bcrypt('Admin123!'),
            'role' => 'admin',
            'is_active' => true,
        ]
    );
}


}
