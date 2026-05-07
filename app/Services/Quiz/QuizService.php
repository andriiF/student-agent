<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Quiz;
use App\Repositories\Quiz\QuizRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuizService
{
    public function __construct(protected QuizRepository $quizRepository)
    {
    }

    public function getPaginated(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->quizRepository->paginate($search, $perPage);
    }

    public function create(array $data): Quiz
    {
        $quiz = $this->quizRepository->create([
            'name' => $data['name'],
        ]);

        if (!empty($data['topic_ids'])) {
            $quiz->topics()->sync($data['topic_ids']);
        }

        return $quiz;
    }

    public function update(Quiz $quiz, array $data): void
    {
        $this->quizRepository->update($quiz, [
            'name' => $data['name'],
        ]);

        if (array_key_exists('topic_ids', $data)) {
            $quiz->topics()->sync($data['topic_ids']);
        }
    }

    public function delete(Quiz $quiz): void
    {
        $this->quizRepository->delete($quiz);
    }
}
