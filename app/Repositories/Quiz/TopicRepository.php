<?php

namespace App\Repositories\Quiz;

use App\Models\Quiz\Topic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TopicRepository
{
    public function paginate(?string $search = null, int $perPage = 15, array $with = []): LengthAwarePaginator
    {
        return Topic::query()
            ->when($search, fn ($q, $s) => $q->where('name', 'ilike', "%{$s}%"))
            ->with($with)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function get(): Collection
    {
        return Topic::query()->get();
    }

    public function create(array $attributes): Topic
    {
        return Topic::query()->create($attributes);
    }

    public function findOrCreate(array $attributes): Topic
    {
        $row = Topic::query()->where($attributes)->first();

        if ($row) {
            return $row;
        }

        return $this->create($attributes);
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
