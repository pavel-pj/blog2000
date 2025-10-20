<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Subject;


class SubjectOptions extends Model
{
    use HasUuids;
 
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'subject_options';
    public $timestamps = false;

    protected $fillable = [
        'subject_id',
        'total_rows',
        'new_words',
        'important_words',
        'repetition_type' 
    ];

    protected $casts = [
        'id' => 'string', // важно для UUID

    ];

    public function subject(): HasOne
    {
        return $this->hasOne(Subject::class);
    }

}
