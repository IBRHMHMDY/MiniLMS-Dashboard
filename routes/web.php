<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Filament::auth()->check()) {
        if (Filament::auth()->user()->hasRole('admin')) {
            return '/admin'; // توجيه الأدمن للوحته
        }
        if (Filament::auth()->user()->hasRole('instructor')) {
            return '/instructor'; // توجيه المدرب للوحته
        }
        return null;
    }
});





// Route::get('/', function () {
//     // إذا كان المستخدم مسجل الدخول ووصل للمسار الرئيسي بالخطأ، نوجهه للوحته الصحيحة
//     if (Filament::auth()->check()) {
//         $user = Filament::auth()->user();
//         if ($user->hasRole(['Super Admin', 'Admin', 'admin'])) {
//             return redirect('/admin');
//         }
//         if ($user->hasRole(['Instructor', 'instructor'])) {
//             return redirect('/instructor');
//         }
//     }
    
//     // إذا لم يكن مسجلاً، يذهب لتسجيل دخول الأدمن
//     return redirect('/admin/login');
// })->name('login');