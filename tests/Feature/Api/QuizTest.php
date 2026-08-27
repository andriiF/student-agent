<?php

namespace Tests\Feature\Api;

use App\Models\Quiz\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFrontendApi;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use InteractsWithFrontendApi;
    use RefreshDatabase;

    public function test_quiz_routes_require_authentication(): void
    {
        $quiz = Quiz::query()->create(['name' => 'Unauthenticated quiz']);

        $this->getJson('/api/quiz/'.$quiz->uuid)->assertUnauthorized();
        $this->postJson('/api/quiz', ['name' => 'New', 'topic_id' => 'x'])->assertUnauthorized();
    }

    public function test_quiz_index_returns_not_found(): void
    {
        $this->actingAsFrontend();

        $this->getJson('/api/quiz')->assertNotFound();
    }

    public function test_user_can_show_quiz(): void
    {
        $user = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);
        $question = $this->createOwnedQuestion($user, $quiz);

        $this->getJson('/api/quiz/'.$quiz->uuid)
            ->assertOk()
            ->assertJsonPath('uuid', $quiz->uuid)
            ->assertJsonPath('name', 'Sample quiz')
            ->assertJsonPath('questions.0.uuid', $question->uuid)
            ->assertJsonPath('questions.0.owner_id', $user->uuid);
    }

    public function test_user_can_create_quiz(): void
    {
        $user = $this->actingAsFrontend();
        ['topic' => $topic] = $this->createOwnedQuiz($user);

        $this->postJson('/api/quiz', [
            'name' => 'New quiz',
            'topic_id' => $topic->uuid,
        ])->assertOk()
            ->assertJsonPath('message', 'Topic created successfully');

        $this->assertDatabaseHas('quizzes', [
            'name' => 'New quiz',
        ]);

        $quiz = Quiz::query()->where('name', 'New quiz')->first();

        $this->assertTrue($topic->fresh()->quizzes->contains('uuid', $quiz->uuid));
    }

    public function test_create_quiz_validates_payload(): void
    {
        $this->actingAsFrontend();

        $this->postJson('/api/quiz', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'topic_id']);
    }

    public function test_user_can_update_quiz(): void
    {
        $user = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);

        $this->putJson('/api/quiz/'.$quiz->uuid, [
            'name' => 'Renamed quiz',
        ])->assertOk()
            ->assertJsonPath('message', 'Topic updated successfully');

        $this->assertDatabaseHas('quizzes', [
            'uuid' => $quiz->uuid,
            'name' => 'Renamed quiz',
        ]);
    }
}
