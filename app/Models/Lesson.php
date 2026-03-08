<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'video_url',
        'order_number',
    ];

    // هذا سيقوم بإضافة حقل is_completed في الـ JSON تلقائياً
    protected $appends = ['is_completed'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }

    public function usersWhoCompleted()
    {
        return $this->belongsToMany(User::class, 'lesson_user')->withTimestamps();
    }

    public function getIsCompletedAttribute(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return $this->usersWhoCompleted()->where('user_id', auth()->id())->exists();
    }
}
