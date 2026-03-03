<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitQuizRequest;
use App\Services\QuizService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    use ApiResponseTrait;

    protected QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * عرض الاختبار وأسئلته للطلاب
     */
    public function show(int $courseId): JsonResponse
    {
        try {
            $userId = auth()->id(); // 👈 جلب ID الطالب الحالي

            // 👈 تمرير الـ userId كمعامل ثاني للدالة
            $quiz = $this->quizService->cloneCourseQuiz($courseId, $userId);

            $quiz->questions->each(function ($question) {
                $question->answers->makeHidden(['is_correct', 'created_at', 'updated_at']);
            });

            return $this->successResponse($quiz, 'تم جلب بيانات الاختبار بنجاح');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * استلام إجابات الطالب وتقييمها
     */
    public function submit(SubmitQuizRequest $request, int $courseId): JsonResponse
    {
        try {
            $userId = auth()->id();
            $answers = $request->validated()['answers'];

            $result = $this->quizService->evaluateAndSaveAttempt($courseId, $userId, $answers);

            return $this->successResponse($result, 'تم تقييم الاختبار بنجاح');

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
