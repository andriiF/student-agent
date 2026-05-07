<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Topic;
use App\Repositories\Quiz\TopicRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TopicService
{
    public function __construct(protected TopicRepository $topicRepository)
    {
    }

    public function getPaginated(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->topicRepository->paginate($search, $perPage);
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
