<?php

namespace App\Models\Quiz;

use App\Models\FrontendUser;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'front_user_id'])]
class Topic extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    public function frontendUser(): BelongsTo
    {
        return $this->belongsTo(FrontendUser::class, 'front_user_id', 'uuid');
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_topic', 'topic_uuid', 'quiz_uuid');
    }
}
