<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SubmitQuizRequest;
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
    public function submit(SubmitQuizRequest $request, $courseId): JsonResponse
    {
        try {
            // استدعاء الـ Service layer
            $result = $this->quizService->submitAndGradeQuiz(
                $request->user()->id,
                $courseId,
                $request->validated('answers')
            );

            // إرجاع Response مطابق للـ Project Bible
            return response()->json([
                'success' => true,
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            // تسجيل الخطأ داخلياً (يفضل استخدام Log::error)
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة الاختبار.',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }
}
