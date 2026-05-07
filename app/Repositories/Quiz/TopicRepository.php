<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\Topic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TopicRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Topic::query()
            ->when($search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $attributes): Topic
    {
        return Topic::query()->create($attributes);
    }

    public function update(Topic $topic, array $attributes): bool
    {
        return $topic->update($attributes);
    }

    public function delete(Topic $topic): ?bool
    {
        return $topic->delete();
    }
}
