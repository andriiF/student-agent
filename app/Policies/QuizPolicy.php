<?php

namespace App\Policies;

use App\Models\FrontendUser;
use App\Models\Quiz\Quiz;

class QuizPolicy
{
    public function view(FrontendUser $user, Quiz $quiz): bool
    {
        return $this->owns($user, $quiz);
    }

    public function update(FrontendUser $user, Quiz $quiz): bool
    {
        return $this->owns($user, $quiz);
    }

    public function delete(FrontendUser $user, Quiz $quiz): bool
    {
        return $this->owns($user, $quiz);
    }

    public function share(FrontendUser $user, Quiz $quiz): bool
    {
        return $this->owns($user, $quiz);
    }

    private function owns(FrontendUser $user, Quiz $quiz): bool
    {
        return $quiz->topics()
            ->where('front_user_id', $user->uuid)
            ->exists();
    }
}
