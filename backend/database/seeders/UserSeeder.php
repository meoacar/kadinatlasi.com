<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@kadinatlasi.com',
            'password' => Hash::make('password'),
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'bio' => 'KadınAtlası.com yöneticisi',
            'interests' => ['yönetim', 'içerik'],
            'points' => 1000,
            'level' => 10,
        ]);

        $admin->assignRole('admin');

        // Create test user
        $user = User::create([
            'name' => 'Test Kullanıcı',
            'email' => 'test@kadinatlasi.com',
            'password' => Hash::make('password'),
            'birth_date' => '1995-06-15',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'bio' => 'Test kullanıcısı',
            'interests' => ['sağlık', 'güzellik', 'astroloji'],
            'last_period_date' => now()->subDays(10),
            'cycle_length' => 28,
            'points' => 150,
            'level' => 2,
        ]);

        $user->assignRole('user');
    }
}