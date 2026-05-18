<?php

namespace App\Services\Quiz;

use App\DTO\AnswerStoreDTO;
use App\Models\FrontendUser;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizPlay;
use App\Repositories\Quiz\AnswerRepository;
use App\Repositories\Quiz\QuizPlayRepository;
use App\Repositories\Quiz\QuizRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class QuizService
{
    public function __construct(protected QuizRepository     $quizRepository,
                                protected QuizPLayRepository $quizPlayRepository,
                                protected AnswerRepository   $answerRepository
    )

    {
    }

    public function getPaginated(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->quizRepository->paginate($search, $perPage);
    }

    public function getAllOrdered(array $columns): Collection
    {
        return $this->quizRepository->getAllOrdered($columns);
    }

    public function create(array $data): Quiz
    {
        $quiz = $this->quizRepository->create([
            'name' => $data['name'],
        ]);

        if (!empty($data['topic_id'])) {
            $quiz->topics()->sync([$data['topic_id']]);
        }

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

        if (array_key_exists('topic_id', $data)) {
            $quiz->topics()->sync([$data['topic_id']]);
        }
        if (array_key_exists('topic_ids', $data)) {
            $quiz->topics()->sync($data['topic_ids']);
        }
    }

    public function delete(Quiz $quiz): void
    {
        $this->quizRepository->delete($quiz);
    }

    public function saveAnswer(FrontendUser $frontUser, Quiz $quiz, AnswerStoreDTO $answerDTO): void
    {

        $qPlay = $this->quizPlayRepository->getOrCreateByUserAndQuiz($frontUser, $quiz);
        $answer = $this->answerRepository->find($answerDTO->answer_id);

        $dataAnswers = [];

        if (!empty($qPlay->answers)) {
            $dataAnswers = $qPlay->answers;
        }

        $dataAnswers[] = [
            'answer_id' => $answerDTO->answer_id,
            'question_id' => $answerDTO->question_id,
            'is_correct' => $answer->is_correct ?? false,
        ];

        $this->quizPlayRepository->update($qPlay, [
            'answers' => $dataAnswers,
            'progress' => floor((count($dataAnswers) / $quiz->questions->count()) * 100),
        ]);
    }


    public function getQuizPlayByUser(FrontendUser $frontUser, Quiz $quiz): ?QuizPlay
    {
        return $this->quizPlayRepository->getOrCreateByUserAndQuiz($frontUser, $quiz);
    }

    public function destroyQuizPlayByUser(FrontendUser $frontUser, Quiz $quiz): void
    {
        $row = $this->getQuizPlayByUser($frontUser, $quiz);

        $row->delete();
    }

    public function getProgress(FrontendUser $frontUser): array
    {
        return $this->quizPlayRepository->getAllByUser($frontUser, [
            'quiz_id',
            'progress'
        ])->toArray();
    }

}
