<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء الأدوار
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'instructor']);
        Role::create(['name' => 'student']);

        // 2. إنشاء حساب مدير النظام (Super Admin)
        $admin = User::firstOrCreate(
            ['email' => 'admin@minilms.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 3. إسناد دور المدير للحساب
        $admin->assignRole('admin');

        // 4. إنشاء حساب مدرب للتجربة
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@minilms.com'],
            [
                'name' => 'Test Instructor',
                'password' => Hash::make('password'),
            ]
        );
        $instructor->assignRole('instructor');
    }
}
