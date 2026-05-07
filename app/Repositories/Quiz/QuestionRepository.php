<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Question::query()
            ->when($search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $attributes): Question
    {
        return Question::query()->create($attributes);
    }

    public function update(Question $question, array $attributes): bool
    {
        return $question->update($attributes);
    }

    public function delete(Question $question): ?bool
    {
        return $question->delete();
    }
}
