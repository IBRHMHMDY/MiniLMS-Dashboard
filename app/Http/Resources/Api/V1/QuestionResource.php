<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            // حماية صارمة: إذا لم تكن هناك إجابات، أرجع مصفوفة فارغة []
            'answers' => $this->getAnswersData($this->whenLoaded('answers')),
        ];
    }

    protected function getAnswersData($answers)
    {
        if (blank($answers)) {
            return [];
        }

        return AnswerResource::collection($answers);
    }
}
