<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordResetService
{
    /**
     * @throws Exception
     */
    public function sendResetToken(string $email): void
    {
        // توليد Token عشوائي (OTP) مكون من 6 أرقام لتسهيل إدخاله في الموبايل
        $token = (string) random_int(100000, 999999);

        // حفظ أو تحديث الـ Token في قاعدة البيانات
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token), // حماية الـ Token في قاعدة البيانات
                'created_at' => Carbon::now(),
            ]
        );

        // محاكاة إرسال الإيميل عن طريق تسجيله في ملف laravel.log
        Log::info("Password Reset OTP for {$email} is: {$token}");
    }

    /**
     * @throws Exception
     */
    public function resetPassword(string $email, string $token, string $newPassword): void
    {
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $resetRecord) {
            throw new Exception('Invalid email or token.');
        }

        // التحقق من صلاحية الـ Token (مثلاً صالح لمدة 60 دقيقة)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            throw new Exception('Token has expired.');
        }

        // التحقق من تطابق الـ Token
        if (! Hash::check($token, $resetRecord->token)) {
            throw new Exception('Invalid token.');
        }

        // تحديث كلمة مرور المستخدم
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($newPassword);
        $user->save();

        // مسح الـ Token بعد الاستخدام
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}
