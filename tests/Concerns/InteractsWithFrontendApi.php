<?php

namespace Tests\Concerns;

use App\Models\FrontendUser;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Topic;
use App\Services\JwtService;

trait InteractsWithFrontendApi
{
    protected function actingAsFrontend(?FrontendUser $user = null): FrontendUser
    {
        $user ??= FrontendUser::factory()->create();

        $token = app(JwtService::class)->generateToken($user);

        $this->withToken($token);

        return $user;
    }

    protected function createOwnedQuiz(FrontendUser $user, string $quizName = 'Sample quiz'): array
    {
        $topic = Topic::query()->create([
            'name' => 'Sample topic',
            'front_user_id' => $user->uuid,
        ]);

        $quiz = Quiz::query()->create([
            'name' => $quizName,
        ]);

        $topic->quizzes()->attach($quiz->uuid);

        return compact('topic', 'quiz');
    }

    protected function createOwnedQuestion(FrontendUser $user, ?Quiz $quiz = null): Question
    {
        if ($quiz === null) {
            ['quiz' => $quiz] = $this->createOwnedQuiz($user);
        }

        return Question::query()->create([
            'name' => 'Sample question',
            'quiz_id' => $quiz->uuid,
            'front_user_id' => $user->uuid,
        ]);
    }
}
