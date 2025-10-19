<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Topic;

class Word extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'subject_id',
        'translation',
        'status',
        'repeated_at'
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID

    ];

    //protected $hidden = ['pivot']; // всегда скрывать pivot

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'topic_words', 'word_id', 'topic_id') ;
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_words', 'word_id', 'task_id');
    }
 
}
