<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'video_url' => $this->video_url,

            // حماية اختبار الدرس من ظهور [null]
            'quiz' => $this->getQuizData($this->whenLoaded('quiz')),
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
