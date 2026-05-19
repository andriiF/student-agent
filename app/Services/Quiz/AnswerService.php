<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Answer;
use App\Repositories\Quiz\AnswerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AnswerService
{
    public function __construct(protected AnswerRepository $answerRepository)
    {
    }

    public function getPaginated(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->answerRepository->paginate($search, $perPage);
    }

    public function create(array $data): Answer
    {
        return $this->answerRepository->create([
            'name' => $data['name'],
            'question_id' => $data['question_id'],
            'is_correct' => $data['is_correct'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'explanation' => $data['explanation'] ?? null,
            'order' => $data['order'] ?? null,
        ]);
    }

    public function update(string $answerId, array $data): void
    {
        $answer = $this->answerRepository->find($answerId);

        $this->answerRepository->update($answer, [
            'name' => $data['name'],
            'question_id' => $data['question_id'] ?? $answer->question_id,
            'is_correct' => $data['is_correct'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'explanation' => $data['explanation'] ?? null,
            'order' => $data['order'] ?? null,
        ]);
    }

    public function delete(Answer $answer): void
    {
        $this->answerRepository->delete($answer);
    }
}
