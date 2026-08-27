<?php

namespace App\Models\Quiz;

use App\Models\FrontendUser;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'quiz_id', 'front_user_id'])]
class Question extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'uuid');
    }

    public function frontendUser(): BelongsTo
    {
        return $this->belongsTo(FrontendUser::class, 'front_user_id', 'uuid');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id', 'uuid');
    }
}
