<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 
class Repetition extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    
    protected $fillable = [
        'id',
        'subject_id',
        'task',
        'answer'
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID

    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
