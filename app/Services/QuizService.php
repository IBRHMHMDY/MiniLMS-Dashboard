<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Course;
use App\Models\QuizAttempt;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizService
{
    /**
     * نسبة النجاح الافتراضية
     */
    const PASSING_SCORE = 50.0;

    /**
     * تصحيح الاختبار وحفظ المحاولة
     */
    public function submitAndGradeQuiz(int $userId, int $courseId, array $submittedAnswers): array
    {
        $totalQuestions = count($submittedAnswers);
        $correctAnswersCount = 0;
        $details = [];

        // 1. جلب الإجابات الصحيحة للأسئلة المرسلة دفعة واحدة (لتحسين الأداء - N+1 Problem Prevention)
        $questionIds = array_column($submittedAnswers, 'question_id');
        $correctAnswersData = DB::table('answers')
            ->whereIn('question_id', $questionIds)
            ->where('is_correct', true)
            ->get()
            ->keyBy('question_id');

        // 2. معالجة وتصحيح كل إجابة وتجهيز مصفوفة التفاصيل
        foreach ($submittedAnswers as $item) {
            $qId = $item['question_id'];
            $selectedAnswerId = $item['answer_id'];

            // تحديد الإجابة الصحيحة من قاعدة البيانات
            $correctAnswerId = isset($correctAnswersData[$qId]) ? $correctAnswersData[$qId]->id : null;

            // هل اختيار الطالب يطابق الإجابة الصحيحة؟
            $isCorrect = ($selectedAnswerId == $correctAnswerId);

            if ($isCorrect) {
                $correctAnswersCount++;
            }

            $details[] = [
                'question_id' => $qId,
                'selected_answer_id' => $selectedAnswerId,
                'correct_answer_id' => $correctAnswerId,
                'is_correct' => $isCorrect,
            ];
        }

        // 3. حساب النسبة المئوية والنتيجة النهائية
        $score = $totalQuestions > 0 ? round(($correctAnswersCount / $totalQuestions) * 100, 2) : 0;
        $isPassed = $score >= self::PASSING_SCORE;

        // 4. الحفظ في قاعدة البيانات باستخدام Transaction لضمان سلامة البيانات
        DB::beginTransaction();
        try {
            // حفظ المحاولة الأساسية
            $attempt = QuizAttempt::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswersCount,
                'score' => $score,
                'is_passed' => $isPassed,
            ]);

            // تحضير التفاصيل للحفظ السريع (Bulk Insert)
            $attemptAnswers = array_map(function ($detail) use ($attempt) {
                return [
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $detail['question_id'],
                    'selected_answer_id' => $detail['selected_answer_id'],
                    'is_correct' => $detail['is_correct'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $details);

            DB::table('quiz_attempt_answers')->insert($attemptAnswers);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        // 5. إرجاع الـ Response المعتمد في الـ Contract
        return [
            'score' => $score,
            'passed' => $isPassed,
            'correct_answers' => $correctAnswersCount,
            'total_questions' => $totalQuestions,
            'message' => $isPassed
                ? 'تهانينا! لقد اجتزت الاختبار بنجاح.'
                : 'للأسف لم تجتز الاختبار، حظاً أوفر في المرة القادمة.',
            'details' => $details, // 👈 هذه هي الإضافة السحرية لتطبيق فلاتر
        ];
    }

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
