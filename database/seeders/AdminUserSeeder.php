<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (User::where('email', 'admin@nina-travel.com')->exists()) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@nina-travel.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@nina-travel.com');
        $this->command->info('Password: admin123');
        $this->command->warn('Please change the password after first login!');
    }
}
