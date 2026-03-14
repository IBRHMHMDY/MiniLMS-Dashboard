<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // 1. فحص ما إذا كان المستخدم مسجل الدخول
    if (Filament::auth()->check()) {
        $user = Filament::auth()->user();
        
        // 2. توجيه الإدارة (استخدام redirect وليس نص)
        if ($user->hasRole(['Super Admin', 'Admin', 'admin'])) {
            return redirect('/admin');
        }
        
        // 3. توجيه المدرب
        if ($user->hasRole(['Instructor', 'instructor'])) {
            return redirect('/instructor');
        }
    }
    
    // 4. المسار الافتراضي لأي زائر غير مسجل الدخول
    return redirect('/admin/login');
})->name('login');