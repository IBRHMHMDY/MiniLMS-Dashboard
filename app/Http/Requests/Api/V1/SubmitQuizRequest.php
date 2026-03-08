<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        // مسموح فقط للمستخدمين المسجلين الدخول (يتم حمايته بواسطة Sanctum في الـ Routes)
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer', 'exists:questions,id'],
            'answers.*.answer_id' => ['required', 'integer', 'exists:answers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'يجب إرسال إجابة واحدة على الأقل.',
            'answers.array' => 'صيغة الإجابات غير صحيحة.',
            'answers.*.question_id.exists' => 'السؤال غير موجود في قاعدة البيانات.',
            'answers.*.answer_id.exists' => 'الإجابة غير موجودة في قاعدة البيانات.',
        ];
    }
}
