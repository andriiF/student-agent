<?php

namespace App\Models;

use App\Models\Quiz\Topic;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['firstname', 'lastname', 'phone', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class FrontendUser extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'front_user_id', 'uuid');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
