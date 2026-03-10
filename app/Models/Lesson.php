<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $course_id
 * @property string $title
 * @property string|null $content
 * @property string|null $video_url
 * @property int $order_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read bool $is_completed
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quiz
 * @property-read int|null $quiz_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $usersWhoCompleted
 * @property-read int|null $users_who_completed_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereVideoUrl($value)
 * @mixin \Eloquent
 */
class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'video_url',
        'order_number',
        'is_published',
    ];

    // هذا سيقوم بإضافة حقل is_completed في الـ JSON تلقائياً
    protected $appends = ['is_completed'];

    protected $casts = [
        'is_published' => 'boolean', // 👈 لضمان تحويله دائماً لـ true/false
    ];
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
        if (!Filament::auth()->check()) {
            return false;
        }

        return $this->usersWhoCompleted()->where('user_id', Filament::auth()->id())->exists();
    }
}
