<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizEditResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'topics' => TopicResource::collection($this->whenLoaded('topics')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
