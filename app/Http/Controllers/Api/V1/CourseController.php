<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CourseResource;
use App\Http\Resources\V1\LessonResource;
use App\Models\Course;
use App\Services\CourseService;
use App\Traits\ApiResponseTrait;
use Exception;
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

    public function show(Course $course): JsonResponse
    {
        $course->load(['instructor', 'category']);

        return $this->successResponse(
            new CourseResource($course),
            'Course details retrieved successfully'
        );
    }

    public function enroll(Request $request, Course $course): JsonResponse
    {
        try {
            $data = $this->courseService->enrollUser($request->user(), $course);

            return $this->successResponse($data, 'Successfully enrolled in the course', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), ['course_id' => [$e->getMessage()]], 422);
        }
    }

    public function lessons(Request $request, Course $course): JsonResponse
    {
        // التحقق من صلاحية رؤية الدروس (يجب أن يكون مشتركاً)
        $isEnrolled = $request->user()->enrollments()->where('course_id', $course->id)->exists();

        if (! $isEnrolled) {
            return $this->errorResponse('Unauthorized. You must enroll in the course first.', [], 403);
        }

        $lessons = $course->lessons()->orderBy('order_number')->get();

        return $this->successResponse(
            LessonResource::collection($lessons),
            'Lessons retrieved successfully'
        );
    }
}
