<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Question;
use App\Repositories\Quiz\QuestionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionService
{
    public function __construct(protected QuestionRepository $questionRepository)
    {
    }

    public function getPaginated(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->questionRepository->paginate($search, $perPage);
    }

    public function create(array $data): Question
    {
        return $this->questionRepository->create([
            'name' => $data['name'],
            'quiz_id' => $data['quiz_id'],
        ]);
    }

    public function update(Question $question, array $data): void
    {
        $this->questionRepository->update($question, [
            'name' => $data['name'],
            'quiz_id' => $data['quiz_id'],
        ]);
    }

    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question);
    }
}
