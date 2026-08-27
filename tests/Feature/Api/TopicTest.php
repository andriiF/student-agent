<?php

namespace Tests\Feature\Api;

use App\Models\FrontendUser;
use App\Models\Quiz\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFrontendApi;
use Tests\TestCase;

class TopicTest extends TestCase
{
    use InteractsWithFrontendApi;
    use RefreshDatabase;

    public function test_topics_require_authentication(): void
    {
        $this->getJson('/api/topic')->assertUnauthorized();
    }

    public function test_user_can_list_own_topics(): void
    {
        $user = $this->actingAsFrontend();
        $other = FrontendUser::factory()->create();

        ['topic' => $ownedTopic] = $this->createOwnedQuiz($user, 'Owned quiz');
        Topic::query()->create([
            'name' => 'Other topic',
            'front_user_id' => $other->uuid,
        ]);

        $response = $this->getJson('/api/topic');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.uuid', $ownedTopic->uuid)
            ->assertJsonPath('data.0.name', 'Sample topic')
            ->assertJsonPath('data.0.quizzes.0.name', 'Owned quiz');
    }

    public function test_user_can_create_topic(): void
    {
        $user = $this->actingAsFrontend();

        $this->postJson('/api/topic', [
            'name' => 'Biology',
        ])->assertOk()
            ->assertJsonPath('message', 'Topic created successfully');

        $this->assertDatabaseHas('topics', [
            'name' => 'Biology',
            'front_user_id' => $user->uuid,
        ]);
    }

    public function test_create_topic_validates_name(): void
    {
        $this->actingAsFrontend();

        $this->postJson('/api/topic', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_show_topic(): void
    {
        $user = $this->actingAsFrontend();
        ['topic' => $topic, 'quiz' => $quiz] = $this->createOwnedQuiz($user);

        $this->getJson('/api/topic/'.$topic->uuid)
            ->assertOk()
            ->assertJsonPath('uuid', $topic->uuid)
            ->assertJsonPath('quizzes.0.uuid', $quiz->uuid);
    }

    public function test_user_can_update_topic(): void
    {
        $user = $this->actingAsFrontend();
        ['topic' => $topic] = $this->createOwnedQuiz($user);

        $this->putJson('/api/topic/'.$topic->uuid, [
            'name' => 'Updated topic',
        ])->assertOk()
            ->assertJsonPath('message', 'Topic created successfully');

        $this->assertDatabaseHas('topics', [
            'uuid' => $topic->uuid,
            'name' => 'Updated topic',
            'front_user_id' => $user->uuid,
        ]);
    }
}
