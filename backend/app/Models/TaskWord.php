<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TaskWord extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;  

    protected $fillable = [
        'id',
        'word_id',
        'task_id',
        'status'
         
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
 
}
