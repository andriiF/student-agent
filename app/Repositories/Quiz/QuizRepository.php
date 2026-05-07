<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\Quiz;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class QuizRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Quiz::query()
            ->when($search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAllOrdered(array $columns): Collection
    {
        return Quiz::query()->select($columns)->orderBy('uuid')->get();
    }

    public function create(array $attributes): Quiz
    {
        return Quiz::query()->create($attributes);
    }

    public function update(Quiz $quiz, array $attributes): bool
    {
        return $quiz->update($attributes);
    }

    public function delete(Quiz $quiz): ?bool
    {
        return $quiz->delete();
    }
}
