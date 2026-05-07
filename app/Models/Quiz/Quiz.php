<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Quiz extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'quiz_topic', 'quiz_uuid', 'topic_uuid');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'quiz_id', 'uuid');
    }
}
