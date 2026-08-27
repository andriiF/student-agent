<?php

namespace Tests\Feature\Api;

use App\Models\FrontendUser;
use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFrontendApi;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use InteractsWithFrontendApi;
    use RefreshDatabase;

    public function test_question_routes_require_authentication(): void
    {
        $user = FrontendUser::factory()->create();
        $question = $this->createOwnedQuestion($user);

        $this->getJson('/api/question/'.$question->uuid)->assertUnauthorized();
        $this->postJson('/api/question', ['name' => 'Q', 'quiz' => 'x'])->assertUnauthorized();
        $this->deleteJson('/api/question/'.$question->uuid)->assertUnauthorized();
    }

    public function test_question_index_returns_not_found(): void
    {
        $this->actingAsFrontend();

        $this->getJson('/api/question')->assertNotFound();
    }

    public function test_user_can_show_question(): void
    {
        $user = $this->actingAsFrontend();
        $question = $this->createOwnedQuestion($user);

        Answer::query()->create([
            'name' => 'Correct answer',
            'question_id' => $question->uuid,
            'is_correct' => true,
            'is_active' => true,
        ]);

        $this->getJson('/api/question/'.$question->uuid)
            ->assertOk()
            ->assertJsonPath('uuid', $question->uuid)
            ->assertJsonPath('owner_id', $user->uuid)
            ->assertJsonPath('answers.0.name', 'Correct answer');
    }

    public function test_user_can_create_question_with_answers(): void
    {
        $user = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);

        $this->postJson('/api/question', [
            'name' => 'What is 2+2?',
            'quiz' => $quiz->uuid,
            'answers' => [
                [
                    'name' => '4',
                    'is_correct' => true,
                    'is_active' => true,
                    'explanation' => 'Basic math',
                ],
                [
                    'name' => '5',
                    'is_correct' => false,
                    'is_active' => true,
                    'explanation' => null,
                ],
            ],
        ])->assertOk()
            ->assertJsonPath('message', 'Question created successfully');

        $question = Question::query()->where('name', 'What is 2+2?')->first();

        $this->assertNotNull($question);
        $this->assertSame($user->uuid, $question->front_user_id);
        $this->assertSame($quiz->uuid, $question->quiz_id);
        $this->assertDatabaseCount('answers', 2);
    }

    public function test_create_question_validates_payload(): void
    {
        $this->actingAsFrontend();

        $this->postJson('/api/question', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'quiz']);
    }

    public function test_owner_can_delete_question(): void
    {
        $user = $this->actingAsFrontend();
        $question = $this->createOwnedQuestion($user);

        $this->deleteJson('/api/question/'.$question->uuid)
            ->assertOk()
            ->assertJsonPath('message', 'Question deleted successfully');

        $this->assertDatabaseMissing('questions', [
            'uuid' => $question->uuid,
        ]);
    }

    public function test_non_owner_cannot_delete_question(): void
    {
        $owner = FrontendUser::factory()->create();
        $question = $this->createOwnedQuestion($owner);

        $this->actingAsFrontend();

        $this->deleteJson('/api/question/'.$question->uuid)
            ->assertForbidden();

        $this->assertDatabaseHas('questions', [
            'uuid' => $question->uuid,
        ]);
    }

    public function test_user_cannot_delete_question_without_owner(): void
    {
        $user = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($user);

        $question = Question::query()->create([
            'name' => 'Legacy question',
            'quiz_id' => $quiz->uuid,
            'front_user_id' => null,
        ]);

        $this->deleteJson('/api/question/'.$question->uuid)
            ->assertForbidden();

        $this->assertDatabaseHas('questions', [
            'uuid' => $question->uuid,
        ]);
    }

    public function test_user_can_update_question(): void
    {
        $user = $this->actingAsFrontend();
        $question = $this->createOwnedQuestion($user);

        $this->putJson('/api/question/'.$question->uuid, [
            'name' => 'Updated question',
            'quiz' => $question->quiz_id,
            'answers' => [],
        ])->assertOk()
            ->assertJsonPath('message', 'Question created successfully');

        $this->assertDatabaseHas('questions', [
            'uuid' => $question->uuid,
            'name' => 'Updated question',
        ]);
    }
}
