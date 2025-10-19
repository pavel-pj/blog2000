<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Topic;
use App\Models\Repetition;
use App\Models\Word;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 

class Subject extends Model
{
    use HasUuids;
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'user_id'
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID

    ];

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }

    public function repetitions(): HasMany
    {
        return $this->hasMany(Repetition::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }
 
}
