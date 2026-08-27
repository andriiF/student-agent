<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Question;
use App\Repositories\Quiz\QuestionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionService
{
    public function __construct(protected QuestionRepository $questionRepository, protected AnswerService $answerService)
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
            'front_user_id' => $data['front_user_id'] ?? null,
        ]);
    }

    public function update(Question $question, array $data): void
    {
        $this->questionRepository->update($question, [
            'name' => $data['name'],
        ]);
    }

    public function updateOrDeleteAnswers(Question $question, array $answers): void
    {
        if (empty($answers)) {
            $question->answers()->delete();
            return;
        }

        $updatedAnswerIds = [];
        foreach ($answers as $answer) {
            if ($answer['uuid']) {
                $this->answerService->update($answer['uuid'], [
                    'name' => $answer['name'],
                    'is_correct' => $answer['is_correct'],
                    'is_active' => $answer['is_active'],
                    'explanation' => $answer['explanation'],
                ]);
                $updatedAnswerIds[] = $answer['uuid'];
                continue;
            }
            $newAnswer = $this->answerService->create([
                'question_id' => $question->uuid,
                'name' => $answer['name'],
                'is_correct' => $answer['is_correct'],
                'is_active' => $answer['is_active'],
                'explanation' => $answer['explanation'],
            ]);

            $updatedAnswerIds[] = $newAnswer['uuid'];
        }
        $question->answers()->whereNotIn('uuid', $updatedAnswerIds)->delete();
    }

    public function delete(Question $question): void
    {
        $this->updateOrDeleteAnswers($question, []);
        $this->questionRepository->delete($question);
    }
}
