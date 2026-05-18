<?php

namespace App\Http\Requests\Api\Quiz;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'answers' => ['array'],
            'quiz' => ['required', 'string', 'max:255']
        ];
    }
}
