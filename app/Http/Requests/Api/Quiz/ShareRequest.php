<?php

namespace App\Http\Requests\Api\Quiz;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quiz_ids' => ['required', 'array', 'min:1'],
            'quiz_ids.*' => ['required', 'string', 'exists:quizzes,uuid'],
            'emails' => ['required', 'array', 'min:1', 'max:10'],
            'emails.*' => ['required', 'email', 'max:255'],
        ];
    }
}
