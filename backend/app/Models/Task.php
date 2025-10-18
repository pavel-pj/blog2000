<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasUuids;
    

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    

    protected $fillable = [
        'id',
        'repetition_id',
        'task',
        'answer',
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID

    ];

    public function repetition(): BelongsTo
    {
        return $this->belongsTo(Repetition::class);
    }
}
