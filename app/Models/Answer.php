<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'answer_text', 'is_correct'];

    // إخفاء حقل is_correct عند إرجاع البيانات في الـ API لمنع الغش في الموبايل
    protected $hidden = ['is_correct', 'created_at', 'updated_at'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
