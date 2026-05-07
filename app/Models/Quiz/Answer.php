<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'question_id', 'is_correct', 'is_active', 'explanation', 'order'])]
class Answer extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'uuid');
    }
}
