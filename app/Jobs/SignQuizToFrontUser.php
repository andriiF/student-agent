<?php

namespace App\Jobs;

use App\Repositories\FrontendUserRepository;
use App\Repositories\Quiz\TopicRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SignQuizToFrontUser implements ShouldQueue
{
    use Queueable;

    /**
     * @param  list<string>  $quizIds
     */
    public function __construct(
        public string $email,
        public array $quizIds,
    ) {
    }

    public function handle(
        FrontendUserRepository $frontendUserRepository,
        TopicRepository $topicRepository,
    ): void {
        $frontUser = $frontendUserRepository->findByEmail($this->email);

        if (!$frontUser) {
            Log::warning('SignQuizToFrontUser: frontend user not found', [
                'email' => $this->email,
            ]);

            return;
        }

        $quizIds = array_values(array_unique(array_filter($this->quizIds)));

        if ($quizIds === []) {
            return;
        }

        $topic = $topicRepository->create([
            'name' => 'Shared quizzes ['.date('Y-m-d').']',
            'front_user_id' => $frontUser->uuid,
        ]);

        $topic->quizzes()->syncWithoutDetaching($quizIds);
    }
}
