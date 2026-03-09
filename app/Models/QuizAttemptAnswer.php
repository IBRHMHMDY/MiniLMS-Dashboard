<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $quiz_attempt_id
 * @property int $question_id
 * @property int $selected_answer_id
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\QuizAttempt $attempt
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\Answer $selectedAnswer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereQuizAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereSelectedAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'selected_answer_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedAnswer()
    {
        return $this->belongsTo(Answer::class, 'selected_answer_id');
    }
}
