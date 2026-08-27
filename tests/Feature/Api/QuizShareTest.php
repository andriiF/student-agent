<?php

namespace Tests\Feature\Api;

use App\Jobs\SignQuizToFrontUser;
use App\Models\FrontendUser;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\Concerns\InteractsWithFrontendApi;
use Tests\TestCase;

class QuizShareTest extends TestCase
{
    use InteractsWithFrontendApi;
    use RefreshDatabase;

    public function test_share_requires_authentication(): void
    {
        $this->postJson('/api/quiz/share', [
            'quiz_ids' => ['00000000-0000-0000-0000-000000000001'],
            'emails' => ['friend@example.com'],
        ])->assertUnauthorized();
    }

    public function test_owner_can_share_quiz(): void
    {
        Queue::fake();

        $owner = $this->actingAsFrontend();
        ['quiz' => $quiz] = $this->createOwnedQuiz($owner);
        $recipient = FrontendUser::factory()->create(['email' => 'friend@example.com']);

        $this->postJson('/api/quiz/share', [
            'quiz_ids' => [$quiz->uuid],
            'emails' => [$recipient->email],
        ])->assertOk()
            ->assertJsonPath('message', 'Quiz share successfully');

        Queue::assertPushed(SignQuizToFrontUser::class, function (SignQuizToFrontUser $job) use ($quiz, $recipient) {
            return $job->email === $recipient->email
                && $job->quizIds === [$quiz->uuid];
        });
    }

    public function test_non_owner_cannot_share_quiz(): void
    {
        Queue::fake();

        $owner = FrontendUser::factory()->create();
        ['quiz' => $quiz] = $this->createOwnedQuiz($owner);

        $this->actingAsFrontend();

        $this->postJson('/api/quiz/share', [
            'quiz_ids' => [$quiz->uuid],
            'emails' => ['friend@example.com'],
        ])->assertForbidden();

        Queue::assertNothingPushed();
    }

    public function test_share_validates_payload(): void
    {
        $this->actingAsFrontend();

        $this->postJson('/api/quiz/share', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quiz_ids', 'emails']);

        $this->postJson('/api/quiz/share', [
            'quiz_ids' => ['not-a-real-quiz-id'],
            'emails' => ['not-an-email'],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['quiz_ids.0', 'emails.0']);
    }

    public function test_share_job_assigns_quizzes_to_recipient_topic(): void
    {
        $owner = FrontendUser::factory()->create();
        ['quiz' => $quiz] = $this->createOwnedQuiz($owner);
        $recipient = FrontendUser::factory()->create(['email' => 'friend@example.com']);

        (new SignQuizToFrontUser($recipient->email, [$quiz->uuid]))->handle(
            app(\App\Repositories\FrontendUserRepository::class),
            app(\App\Repositories\Quiz\TopicRepository::class),
        );

        $sharedTopic = Topic::query()
            ->where('front_user_id', $recipient->uuid)
            ->where('name', 'like', 'Shared quizzes [%')
            ->first();

        $this->assertNotNull($sharedTopic);
        $this->assertTrue($sharedTopic->quizzes->contains('uuid', $quiz->uuid));
    }

    public function test_share_job_skips_unknown_email(): void
    {
        $quiz = Quiz::query()->create(['name' => 'Orphan quiz']);

        (new SignQuizToFrontUser('missing@example.com', [$quiz->uuid]))->handle(
            app(\App\Repositories\FrontendUserRepository::class),
            app(\App\Repositories\Quiz\TopicRepository::class),
        );

        $this->assertDatabaseCount('topics', 0);
    }
}
