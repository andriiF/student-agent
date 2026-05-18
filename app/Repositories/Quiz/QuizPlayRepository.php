<?php

namespace App\Repositories\Quiz;

use App\Models\FrontendUser;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizPlay;

class QuizPlayRepository
{

    public function getAllByUser(FrontendUser $user, ?array $fields)
    {
        if (!$fields) {
            $fields = ['*'];
        }

        return QuizPlay::query()->where('front_user_id', $user->uuid)->get($fields);
    }

    public function getOrCreateByUserAndQuiz(FrontendUser $user, Quiz $quiz): QuizPlay
    {
        $row = QuizPlay::query()->where([
            'front_user_id' => $user->uuid,
            'quiz_id' => $quiz->uuid,
        ])->first();

        if ($row) {
            return $row;
        }

        return $this->create([
            'front_user_id' => $user->uuid,
            'quiz_id' => $quiz->uuid,
        ]);
    }

    public function create(array $attributes): QuizPlay
    {
        return QuizPlay::query()->create($attributes);
    }

    public function update(QuizPlay $quiz, array $attributes): bool
    {
        return $quiz->update($attributes);
    }

    public function delete(QuizPlay $quiz): ?bool
    {
        return $quiz->delete();
    }
}
