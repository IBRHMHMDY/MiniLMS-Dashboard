<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    public function run(): void
    {

        // 2. إنشاء حساب مدير النظام (Super Admin)
        $admin = User::firstOrCreate(
            ['email' => 'admin@minilms.com'],
            [
                'name' => 'Test Admin',
                'password' => 'password',
                'status' => 'active',
            ]
        );

        // 3. إسناد دور المشرف للحساب
        $admin->assignRole('admin');
    }
}
