<?php

namespace App\Http\Requests\Admin\Quiz;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
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
            'name'        => ['required', 'string', 'max:255'],
            'question_id' => ['required', 'string', 'exists:questions,uuid'],
            'is_correct'  => ['boolean'],
            'is_active'   => ['boolean'],
            'explanation' => ['nullable', 'string'],
            'order'       => ['nullable', 'integer'],
        ];
    }
}
