<?php

namespace Tests\Feature\Api;

use App\Models\Quiz\Answer;
use App\Models\Quiz\QuizPlay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFrontendApi;
use Tests\TestCase;

class QuizPlayTest extends TestCase
{
    use InteractsWithFrontendApi;
    use RefreshDatabase;

    public function test_quiz_play_requires_authentication(): void
    {
        $user = \App\Models\FrontendUser::factory()->create();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);

        $this->getJson('/api/quizplay/'.$quiz->uuid)->assertUnauthorized();
    }

    public function test_user_can_start_quiz_play(): void
    {
        $user = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);

        $this->getJson('/api/quizplay/'.$quiz->uuid)
            ->assertCreated()
            ->assertJsonStructure(['answers']);

        $this->assertDatabaseHas('quiz_plays', [
            'front_user_id' => $user->uuid,
            'quiz_id' => $quiz->uuid,
        ]);
    }

    public function test_user_can_save_answer(): void
    {
        $user = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);
        $question = $this->createOwnedQuestion($user, $quiz);
        $answer = Answer::query()->create([
            'name' => '4',
            'question_id' => $question->uuid,
            'is_correct' => true,
            'is_active' => true,
        ]);

        $this->postJson('/api/quizplay/'.$quiz->uuid, [
            'question_id' => $question->uuid,
            'answer_id' => $answer->uuid,
        ])->assertOk()
            ->assertJsonPath('message', 'Answer saved successfully');

        $play = QuizPlay::query()
            ->where('front_user_id', $user->uuid)
            ->where('quiz_id', $quiz->uuid)
            ->first();

        $this->assertNotNull($play);
        $this->assertSame(100, $play->progress);
        $this->assertCount(1, $play->answers);
        $this->assertTrue($play->answers[0]['is_correct']);
    }

    public function test_user_can_destroy_quiz_play(): void
    {
        $user = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);

        $this->getJson('/api/quizplay/'.$quiz->uuid)->assertCreated();

        $this->deleteJson('/api/quizplay/'.$quiz->uuid)
            ->assertOk()
            ->assertJsonPath('message', 'Topic deleted successfully');

        $this->assertSoftDeleted('quiz_plays', [
            'front_user_id' => $user->uuid,
            'quiz_id' => $quiz->uuid,
        ]);
    }
}
