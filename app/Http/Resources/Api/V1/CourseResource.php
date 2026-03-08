<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_free' => $this->is_free,
            'price' => $this->price,
            'image_url' => $this->image_url,

            'instructor' => $this->whenLoaded('instructor', function () {
                return [
                    'id' => $this->instructor->id,
                    'name' => $this->instructor->name,
                ];
            }),

            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),

            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),

            // حماية الاختبار النهائي من ظهور [null]
            'final_quiz' => $this->getQuizData($this->whenLoaded('quiz')),
        ];
    }

    protected function getQuizData($quizzes)
    {
        if (blank($quizzes)) {
            return [];
        }
        if (is_iterable($quizzes)) {
            return $quizzes->isEmpty() ? [] : QuizResource::collection($quizzes);
        }

        return [new QuizResource($quizzes)];
    }
}
