<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CourseResource;
use App\Http\Resources\Api\V1\LessonResource;
use App\Models\Course;
use App\Services\CourseService;
use App\Traits\ApiResponseTrait;
use Filament\Facades\Filament;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponseTrait;

    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(): JsonResponse
    {
        $courses = Course::with(['instructor', 'category'])->get();

        return $this->successResponse(
            CourseResource::collection($courses),
            'Courses retrieved successfully'
        );
    }

    public function show($id)
    {
        $course = Course::with([
            'instructor',
            'category',
            'lessons.quiz.questions.answers', // اختبارات الدروس
            'quiz.questions.answers',          // الاختبار النهائي للكورس
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Course details retrieved successfully',
            'data' => new CourseResource($course),
        ]);
    }

    /**
     * الاشتراك في كورس
     */
    public function enroll(Course $course)
    {
        $user = Filament::auth()->user();

        // التحقق مما إذا كان مشتركاً بالفعل
        if ($user->courses()->where('course_id', $course->id)->exists()) {
            return $this->errorResponse('أنت مشترك بالفعل في هذا الكورس', 400);
        }

        // 👈 اللمسة المعمارية: التحقق من الدفع
        if (! $course->is_free) {
            // كود 402 يعني Payment Required في معايير الـ HTTP
            return response()->json([
                'status' => 'payment_required',
                'message' => 'هذا الكورس مدفوع ويتطلب إتمام عملية الدفع.',
                'data' => [
                    'course_id' => $course->id,
                    'price' => $course->price,
                ],
            ], 402);
        }

        // إذا كان مجانياً، يتم التسجيل فوراً
        $user->courses()->attach($course->id);

        return $this->successResponse([], 'تم الاشتراك في الكورس المجاني بنجاح');
    }

    public function lessons(Request $request, Course $course): JsonResponse
    {
        // التحقق من صلاحية رؤية الدروس (يجب أن يكون مشتركاً)
        $isEnrolled = $request->user()->courses()->where('course_id', $course->id)->exists();

        if (! $isEnrolled) {
            return $this->errorResponse('Unauthorized. You must enroll in the course first.', [], 403);
        }

        $lessons = $course->lessons()->orderBy('order_number')->get();

        return $this->successResponse(
            LessonResource::collection($lessons),
            'Lessons retrieved successfully'
        );
    }

    /**
     * تغيير حالة الدروس
     */
    public function toggleLessonCompletion(\App\Models\Lesson $lesson)
    {
        $user = Filament::auth()->user();
        // دالة toggle ستقوم بإضافة الدرس إذا لم يكن موجوداً، أو حذفه إذا كان موجوداً
        $user->completedLessons()->toggle($lesson->id);

        return $this->successResponse(null, 'تم تحديث حالة الدرس بنجاح');
    }

    /**
     * جلب الكورسات التي اشترك فيها الطالب الحالي
     */
    public function myCourses()
    {
        $user = Filament::auth()->user();

        // جلب كورسات الطالب مع عدد الدروس
        $myCourses = $user->courses()->withCount('lessons')->get();

        return $this->successResponse($myCourses, 'تم جلب دوراتك بنجاح');
    }
}
