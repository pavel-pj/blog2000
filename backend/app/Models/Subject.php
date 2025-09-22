<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasUuids;

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

    public function topics(): BelongsTo
    {
        return $this->hasMany(Topic::class);
    }
 
}
