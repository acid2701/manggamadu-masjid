<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'manggamadu2727@gmail.com'],
            [
                'name' => 'AcidPrjct',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_active' => true,
            ],
        );

        $admin->assignRole('super-admin');
    }
}
