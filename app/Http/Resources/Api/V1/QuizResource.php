<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'pass_mark' => $this->pass_mark,
            'questions' => $this->getQuestionsData($this->whenLoaded('questions')),
        ];
    }

    protected function getQuestionsData($questions)
    {
        if (blank($questions)) {
            return [];
        }

        return QuestionResource::collection($questions);
    }
}
