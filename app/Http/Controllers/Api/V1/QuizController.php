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
            $quiz = $this->quizService->cloneCourseQuiz($courseId);

            // بفضل الـ $hidden في الـ Model، الإجابات الصحيحة لن تظهر هنا
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
