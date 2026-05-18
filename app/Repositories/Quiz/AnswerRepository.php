<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\Answer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AnswerRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Answer::query()
            ->when($search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(string $id): ?Answer
    {
        return Answer::query()->find($id);
    }

    public function create(array $attributes): Answer
    {
        return Answer::query()->create($attributes);
    }

    public function update(Answer $answer, array $attributes): bool
    {
        return $answer->update($attributes);
    }

    public function delete(Answer $answer): ?bool
    {
        return $answer->delete();
    }
}
