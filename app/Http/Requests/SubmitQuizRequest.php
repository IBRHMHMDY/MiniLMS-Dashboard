<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'exists:questions,id'],
            'answers.*.answer_id' => ['required', 'exists:answers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'answers.required' => 'يجب إرسال الإجابات.',
            'answers.*.question_id.exists' => 'معرف السؤال غير صالح.',
            'answers.*.answer_id.exists' => 'معرف الإجابة غير صالح.',
        ];
    }
}
