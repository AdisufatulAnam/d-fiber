<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Akun admin operasional untuk login panel dashboard.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dfiber.id'],
            [
                'name' => 'Admin Operasional',
                'password' => 'password',
                'role' => 'admin',
            ],
        );
    }
}
