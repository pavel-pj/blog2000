<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Word extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'subject_id',
        'translation'
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID

    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
