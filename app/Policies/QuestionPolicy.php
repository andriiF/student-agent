<?php

namespace App\Policies;

use App\Models\FrontendUser;
use App\Models\Quiz\Question;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class QuestionPolicy
{
    public function delete(Authenticatable $user, Question $question): bool
    {
        if ($user instanceof User) {
            return true;
        }

        if ($user instanceof FrontendUser) {
            return $question->front_user_id !== null
                && $question->front_user_id === $user->uuid;
        }

        return false;
    }
}
