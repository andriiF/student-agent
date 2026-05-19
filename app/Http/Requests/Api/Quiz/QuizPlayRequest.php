<?php

namespace App\Http\Requests\Api\Quiz;

use App\DTO\AnswerStoreDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class QuizPlayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_id' => 'required|uuid',
            'answer_id' => 'nullable'
        ];
    }

    public function toDTO(): AnswerStoreDTO
    {
        return new AnswerStoreDTO(
            question_id: $this->question_id,
            answer_id: $this->answer_id,
        );
    }
}
