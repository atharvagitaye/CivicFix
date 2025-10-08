<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@civicfix.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // Create admin record
        Admin::updateOrCreate(
            ['user_id' => $adminUser->id]
        );

        // Create default staff user
        $staffUser = User::updateOrCreate(
            ['email' => 'staff@civicfix.com'],
            [
                'name' => 'Staff Member',
                'password' => Hash::make('staff123'),
            ]
        );

        // Create staff record
        Staff::updateOrCreate(
            ['user_id' => $staffUser->id]
        );

        // Create regular test user
        User::updateOrCreate(
            ['email' => 'user@civicfix.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('user123'),
            ]
        );

        $this->command->info('Default users created:');
        $this->command->info('Admin: admin@civicfix.com / admin123');
        $this->command->info('Staff: staff@civicfix.com / staff123');
        $this->command->info('User: user@civicfix.com / user123');
    }
}
