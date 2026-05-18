<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


#[Fillable(['front_user_id', 'quiz_id', 'answers', 'score', 'mode', 'is_completed','progress'])]
class QuizPlay extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'answers' => 'array',
        ];
    }
}
