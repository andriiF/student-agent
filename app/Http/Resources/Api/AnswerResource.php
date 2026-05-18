<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'is_correct' => $this->is_correct,
            'explanation' => $this->explanation,
        ];
    }
}
