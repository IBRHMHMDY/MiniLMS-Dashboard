<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Exception;

class CourseService
{
    /**
     * @throws Exception
     */
    public function enrollUser(User $user, Course $course): array
    {
        // التحقق مما إذا كان الطالب مشتركاً بالفعل
        $alreadyEnrolled = $user->enrollments()->where('course_id', $course->id)->exists();

        if ($alreadyEnrolled) {
            throw new Exception('User is already enrolled in this course.');
        }

        // إنشاء الاشتراك
        $enrollment = $user->enrollments()->create([
            'course_id' => $course->id,
            // 'enrolled_at' takes current timestamp by default from DB
        ]);

        return [
            'enrollment_id' => $enrollment->id,
            'course_id' => $course->id,
            'status' => 'enrolled',
        ];
    }
}
