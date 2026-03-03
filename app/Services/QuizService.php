<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Course;
use App\Models\QuizAttempt;
use Exception;

class QuizService
{
    /**
     * جلب بيانات الاختبار الخاص بالكورس بعد التحقق من اكتمال الدروس
     */
    public function cloneCourseQuiz(int $courseId, int $userId) // 👈 إضافة userId
    {
        $course = Course::findOrFail($courseId);

        // 👈 التحقق من إكمال الدروس
        $totalLessons = $course->lessons()->count();
        $completedLessons = $course->lessons()->whereHas('usersWhoCompleted', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        if ($totalLessons > 0 && $completedLessons < $totalLessons) {
            throw new Exception("يجب إكمال جميع الدروس أولاً ($completedLessons/$totalLessons) قبل دخول الاختبار.");
        }

        $quiz = $course->quiz()->with('questions.answers')->first();
        if (! $quiz) {
            throw new Exception('لا يوجد اختبار متاح لهذا الكورس حالياً.');
        }

        return $quiz;
    }

    /**
     * استلام إجابات الطالب، حساب النتيجة، وتسجيل المحاولة
     */
    public function evaluateAndSaveAttempt(int $courseId, int $userId, array $submittedAnswers)
    {
        $quiz = $this->cloneCourseQuiz($courseId, $userId);
        $questionsCount = $quiz->questions()->count();

        if ($questionsCount === 0) {
            throw new Exception('الاختبار لا يحتوي على أسئلة بعد.');
        }

        $correctAnswersCount = 0;

        // جلب الإجابات الصحيحة من قاعدة البيانات بأمان للمقارنة
        // $submittedAnswers format: [['question_id' => 1, 'answer_id' => 4], ...]
        foreach ($submittedAnswers as $submission) {
            $questionId = $submission['question_id'];
            $selectedAnswerId = $submission['answer_id'];

            // البحث عن الإجابة في قاعدة البيانات والتحقق مما إذا كانت صحيحة
            $isCorrect = Answer::where('id', $selectedAnswerId)
                ->where('question_id', $questionId)
                ->where('is_correct', true)
                ->exists();

            if ($isCorrect) {
                $correctAnswersCount++;
            }
        }

        // حساب النسبة المئوية
        $score = ($correctAnswersCount / $questionsCount) * 100;
        $passed = $score >= $quiz->pass_mark;

        // تسجيل المحاولة في قاعدة البيانات
        $attempt = QuizAttempt::create([
            'user_id' => $userId,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
        ]);

        return [
            'attempt_id' => $attempt->id,
            'score' => round($score, 2),
            'passed' => $passed,
            'pass_mark' => $quiz->pass_mark,
            'correct_answers' => $correctAnswersCount,
            'total_questions' => $questionsCount,
            'message' => $passed ? 'مبروك! لقد اجتزت الاختبار بنجاح.' : 'للأسف، لم تجتز الاختبار. حظاً أوفر في المرة القادمة.',
        ];
    }
}
