<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Topic;
use App\Repositories\Quiz\TopicRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TopicService
{
    public function __construct(protected TopicRepository $topicRepository)
    {
    }

    public function getPaginated(?string $search, int $perPage = 15, array $with = []): LengthAwarePaginator
    {
        return $this->topicRepository->paginate($search, $perPage, $with);
    }

    public function get(): Collection
    {
        return $this->topicRepository->get();
    }

    public function create(array $data): Topic
    {
        return $this->topicRepository->create([
            'name' => $data['name'],
            'front_user_id' => $data['front_user_id'],
        ]);
    }

    public function update(Topic $topic, array $data): void
    {
        $this->topicRepository->update($topic, [
            'name' => $data['name'],
            'front_user_id' => $data['front_user_id'],
        ]);
    }

    public function delete(Topic $topic): void
    {
        $this->topicRepository->delete($topic);
    }
}
